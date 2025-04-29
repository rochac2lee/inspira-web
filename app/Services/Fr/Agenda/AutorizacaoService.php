<?php
namespace App\Services\Fr\Agenda;

use App\Library\Slim;
use App\Models\FrAgendaAutorizacao;
use App\Models\FrAgendaAutorizacoesAutorizadas;
use App\Models\FrTurma;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AutorizacaoService {

    public function __construct()
    {
        $this->guardName = whatGuard();
    }

    public function scopoQueryAutorizacaoResponsavelApi($autorizacao){
        $autorizacao->join('fr_agenda_autorizacao_alunos','fr_agenda_autorizacao.id','fr_agenda_autorizacao_alunos.autorizacao_id')
            ->join('users','users.id','fr_agenda_autorizacao_alunos.aluno_id')
            ->selectRaw('distinct fr_agenda_autorizacao.*, users.id as aluno_id, users.name as aluno_name, users.nome_completo as aluno_nome_completo, users.avatar_social, users.img_perfil, fr_agenda_autorizacao_alunos.turma_id');

        $resp = User::with(['alunosDoResponsavel'=>function($q){
                    $q->selectRaw('users.id');
                }])->find(auth()->user()->getRawOriginal('id'));

            $autorizacao = $autorizacao->where(function($q) use($resp){

                foreach($resp->alunosDoResponsavel as $r) {
                    $q->orWhere(function ($query) use ($r){
                        $query->where('fr_agenda_autorizacao_alunos.aluno_id',$r->id)
                            ->where('fr_agenda_autorizacao_alunos.escola_id',$r->pivot->escola_id)
                            ->where('fr_agenda_autorizacao_alunos.instituicao_id',$r->pivot->instituicao_id);
                    });
                }
            });
        return $autorizacao;
    }

    public function scopoQueryDocumentos($dados = null){
        $autorizacao = FrAgendaAutorizacao::with(['escola','usuario']);
        if(auth()->user()->permissao == 'R') {
            if($this->guardName == 'api') {
                $autorizacao = $this->scopoQueryAutorizacaoResponsavelApi($autorizacao);
            }else{
                return false;
            }
        }
        else{
            $autorizacao->where('instituicao_id',auth()->user()->instituicao_id)
                        ->with(['alunos'=>function($q){
                            $q->groupBy('autorizacao_id')->selectRaw('count(aluno_id) as qtd, autorizacao_id');
                        }])
                        ->with(['respondidos'=>function($q){
                            $q->groupBy('autorizacao_id')->selectRaw('count(aluno_id) as qtd, autorizacao_id');
                        }]);
            if(auth()->user()->permissao == 'C' || auth()->user()->permissao == 'P'){
                $autorizacao->where('escola_id',auth()->user()->escola_id);
            }
        }

        if($dados){
            if(isset($dados['nome']) && $dados['nome']!= ''){
                $autorizacao->where(function($q) use($dados){
                    $q->orWhere('titulo','like','%'.$dados['nome'].'%')
                        ->orWhere('id',$dados['nome']);
                });
            }
            if(isset($dados['publicado']) && $dados['publicado']!= ''){
                $autorizacao->where('publicado', $dados['publicado']);
            }
        }


        return $autorizacao;
    }

    private function scopoQueryPermissaoComunicados($query, $permissao){
        if($permissao == 'P'){
            $query = $query->where('user_id',auth()->user()->id);
        }elseif($permissao == 'C'){
            $query = $query->where(function($q){
                $q->orWhere('permissao_usuario','P')
                    ->orWhere('user_id',auth()->user()->id);
            });
        }
        return $query;
    }

    public function lista($dados = null){
        $autorizacao = $this->scopoQueryDocumentos($dados);
        if($this->guardName == 'api'){
            ///// se for para pegar o ultimo id cadastrado para a API
            if(isset($dados['ultimo']) && $dados['ultimo'] == 1){
                return $autorizacao->selectRaw('max(fr_agenda_autorizacao.id) as ultimo')
                    ->first();
            }
        }
        return $autorizacao->orderBy('updated_at','DESC')
            ->paginate(20);
    }

    public function inserir($dados)
    {
        DB::beginTransaction();
        try
        {
            $dados['user_id'] = auth()->user()->id;
            $dados['instituicao_id'] = auth()->user()->instituicao_id;
            $dados['escola_id'] = auth()->user()->escola_id;
            $dados['permissao_usuario'] = auth()->user()->permissao;
            $imagem = $dados['imagem'];
            unset($dados['imagem']);
            $autorizacao = new FrAgendaAutorizacao($dados);
            $autorizacao->save();

            if($imagem != '') {
                $img = [
                    'imagem' => $this->gravaImagem($autorizacao->id),
                ];
                $autorizacao->update($img);
            }

            $alunos = [];
            foreach($dados['turma'] as $t){
                foreach($dados['aluno'][$t] as $a){
                    $alu = [
                        'turma_id' => $t,
                        'aluno_id' => $a,
                        'escola_id' => auth()->user()->escola_id,
                        'instituicao_id' => auth()->user()->instituicao_id,
                    ];
                    $alunos[] = $alu;
                }
            }
            $autorizacao->alunos()->attach($alunos);
            DB::commit();
            if($autorizacao->publicado == 1){
                $this->notificacaoMobile($autorizacao);
            }
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function excluir($id){
        DB::beginTransaction();
        try
        {
            $autorizacao = $this->scopoQueryDocumentos();
            $autorizacao = $this->scopoQueryPermissaoComunicados($autorizacao, auth()->user()->permissao);
            $autorizacao = $autorizacao->where('publicado',0)
                ->selectRaw('distinct fr_agenda_autorizacao.*')
                ->find($id);
            $autorizacao->alunos()->detach();
            $autorizacao->delete();
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function publicar($id){
        DB::beginTransaction();
        try
        {
            $autorizacao = $this->scopoQueryDocumentos();
            $autorizacao = $this->scopoQueryPermissaoComunicados($autorizacao, auth()->user()->permissao);
            $autorizacao = $autorizacao->where('publicado',0)
                ->find($id);
            if($autorizacao){
                $autorizacao->update(['publicado'=>1]);
                DB::commit();
                $this->notificacaoMobile($autorizacao);
                return true;
            }else{
                DB::commit();
                return false;
            }
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    private function notificacaoMobile($documento){
        $dados = [
            'id' => $documento->id,
            'titulo' => 'INspira Agenda - Autorizações',
            'corpo' => $documento->titulo,
            'tipo' => 'agenda',
        ];
        $notificacao = new PushNotificationService();
        $notificacao->addNotificacaoAutorizacao($dados);
    }

    public function get($idDocumento,$ativo=null){
        $autorizacao = $this->scopoQueryDocumentos();

        $autorizacao->with('alunos')
                ->selectRaw('distinct fr_agenda_autorizacao.*');
        if($ativo != null){
            $autorizacao = $autorizacao->where('publicado',$ativo);
        }
        return $autorizacao->find($idDocumento);

    }

    public function update($dados)
    {
        DB::beginTransaction();
        try
        {
            $autorizacao = $this->get($dados['id'],0);
            $imagem = $dados['imagem'];
            unset($dados['imagem']);
            $autorizacao->update($dados);
            if($dados['existeImg']==''){
                if($autorizacao->imagem != ''){
                    $img = [
                        'imagem' => null,
                    ];
                    $caminhoStorage = config('app.frStorage') .'agenda/autorizacao/' .$autorizacao->user_id.'/'.$autorizacao->id.'/'.$autorizacao->imagem;

                    if( Storage::disk()->exists($caminhoStorage) )
                    {
                        Storage::disk()->deleteDirectory(config('app.frStorage') .'agenda/autorizacao/' .$autorizacao->user_id.'/'.$autorizacao->id);
                    }
                }

                if($imagem != ''){
                    $img = [
                        'imagem' => $this->gravaImagem($autorizacao->id),
                    ];
                }
                if(isset($img)) {
                    $autorizacao->update($img);
                }
            }

            $alunos = [];
            foreach($dados['turma'] as $t){
                foreach($dados['aluno'][$t] as $a){
                    $alu = [
                        'turma_id' => $t,
                        'aluno_id' => $a,
                        'escola_id' => auth()->user()->escola_id,
                        'instituicao_id' => auth()->user()->instituicao_id,
                    ];
                    $alunos[] = $alu;
                }
            }
            $autorizacao->alunos()->sync($alunos);
            DB::commit();
            if($autorizacao->publicado == 1){
                $this->notificacaoMobile($autorizacao);
            }
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function getTurmasSelecionadas($dados){
        $turmas = [];
        $alunos = [];
        foreach($dados['turma'] as $e){
            foreach($dados['aluno'][$e] as $t){
                $turmas[$e]= $e;
                $alunos[$t] = $t;
            }
        }
        return FrTurma::join('ciclo_etapas','fr_turmas.ciclo_etapa_id','ciclo_etapas.id')
            ->join('ciclos','ciclo_etapas.ciclo_id','ciclos.id')
            ->with(['qtdAlunos','alunos'=>function($q) use($alunos, $dados){
                $q->join('fr_agenda_autorizacao_alunos',function($join){
                    $join->on('fr_agenda_autorizacao_alunos.aluno_id','=','fr_turma_aluno.aluno_id')
                        ->on('fr_turma_aluno.turma_id','=','fr_agenda_autorizacao_alunos.turma_id');
                })
                    ->whereIn('users.id',$alunos);
                if(isset($dados['autorizacao'])){
                    $q->where('autorizacao_id', $dados['autorizacao']);
                }
            }])
            ->whereIn('fr_turmas.id',$turmas)
            ->orderBy('ciclos.id')
            ->orderBy('ciclo_etapas.id')
            ->orderBy('fr_turmas.titulo')
            ->selectRaw('fr_turmas.*, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo')
            ->get();
    }

    private function gravaImagem($idAutorizacao){
        try {
            $images = Slim::getImages('imagem');
            if(count($images)>0)
            {

                $image = $images[0];
                // let's create some shortcuts
                $name = explode('.',$image['input']['name']);
                $ext = '.'.$name[count($name)-1];
                $data = $image['output']['data'];

                // store the file
                $file = Slim::saveFile($data, $ext,config('app.frTmp'));
                $fileName = $file['name'];

                $img = Image::make(config('app.frTmp').$fileName);
                $img->resize(1000, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('webp', 90);

                $resource = $img->stream()->detach();
                $fileName = $img->filename.'.webp';

                $caminhoStorage = config('app.frStorage') .'agenda/autorizacao/' .auth()->user()->id.'/'.$idAutorizacao.'/'.$fileName;
                Storage::disk()->put($caminhoStorage, $resource);
                return $fileName;
            }
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    public function autorizar($dados){
        DB::beginTransaction();
        try
        {
            $dados['responsavel_id'] = auth()->user()->id;
            $dados['autorizacao_id'] = $dados['id'];
            $temAutorizado = FrAgendaAutorizacoesAutorizadas::where('turma_id', $dados['turma_id'])
                ->where('aluno_id', $dados['aluno_id'])
                ->where('autorizacao_id', $dados['autorizacao_id'])
                ->with('responsavel')
                ->first();
            if($temAutorizado){
                DB::rollback();
                if($temAutorizado->autorizado == 1){
                    return 'Já autorizado por '.$temAutorizado->responsavel->nome;
                }else{
                    return 'Não autorizado por '.$temAutorizado->responsavel->nome;
                }
                return 'Autorização já realizada';
            }
            else{
                $autorizado = new FrAgendaAutorizacoesAutorizadas($dados);
                $autorizado->save();
            }
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function getRecebidos($autorizacaoId, $request=null){
            $recebidos = FrAgendaAutorizacao::join('fr_agenda_autorizacao_alunos', 'fr_agenda_autorizacao_alunos.autorizacao_id','fr_agenda_autorizacao.id')
                ->join('fr_turmas','fr_turmas.id', 'fr_agenda_autorizacao_alunos.turma_id')
                ->join('ciclo_etapas','fr_turmas.ciclo_etapa_id','ciclo_etapas.id')
                ->join('ciclos','ciclo_etapas.ciclo_id','ciclos.id')
                ->join('escolas','escolas.id', 'fr_turmas.escola_id')
                ->join('users','users.id', 'fr_agenda_autorizacao_alunos.aluno_id')
                ->leftJoin('fr_agenda_autorizacao_alunos_autorizado', function($j){
                    $j->on('fr_agenda_autorizacao_alunos_autorizado.autorizacao_id', 'fr_agenda_autorizacao.id')
                        ->on('fr_agenda_autorizacao_alunos_autorizado.turma_id', 'fr_turmas.id')
                        ->on('fr_agenda_autorizacao_alunos_autorizado.aluno_id', 'users.id');
                })
                ->leftJoin('users as responsavel', 'fr_agenda_autorizacao_alunos_autorizado.responsavel_id', 'responsavel.id')
                ->where('fr_agenda_autorizacao_alunos.autorizacao_id',$autorizacaoId)
                ->orderBy('escolas.titulo')
                ->orderBy('ciclos.id')
                ->orderBy('ciclo_etapas.id')
                ->orderBy('fr_turmas.titulo')
                ->orderBy('users.nome_completo')
                ->selectRaw('fr_agenda_autorizacao_alunos_autorizado.*, escolas.titulo as escola, fr_turmas.titulo as turma, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo, users.nome_completo as aluno_nome_completo, users.name as aluno_nome, responsavel.nome_completo as resp_nome_completo, responsavel.name as resp_nome');

        if(auth()->user()->permissao != 'I'){
            $recebidos->where('escolas.id',auth()->user()->escola_id);
        }

            if(isset($request['nome']) && $request['nome']!= ''){
                $recebidos->where(function($q) use($request){
                    $q->orWhere('escolas.titulo','like', '%'.$request['nome'].'%')
                        ->orWhere('fr_turmas.titulo','like', '%'.$request['nome'].'%')
                        ->orWhere('ciclo_etapas.titulo','like', '%'.$request['nome'].'%')
                        ->orWhere('ciclos.titulo','like', '%'.$request['nome'].'%')
                        ->orWhere('users.name','like', '%'.$request['nome'].'%')
                        ->orWhere('users.nome_completo','like', '%'.$request['nome'].'%');
                });
            }
            if(isset($request['tipo']) && $request['tipo']!= '') {
                if($request['tipo'] == 1){
                    $recebidos->where('fr_agenda_autorizacao_alunos_autorizado.autorizado', 1);
                }
                if($request['tipo'] == 2){
                    $recebidos->where('fr_agenda_autorizacao_alunos_autorizado.autorizado', 0);
                }
                if($request['tipo'] == 3){
                    $recebidos->whereNull('fr_agenda_autorizacao_alunos_autorizado.autorizado');
                }
            }

            return $recebidos->paginate(20);
    }
}
