<?php
namespace App\Services\Fr\Agenda;

use App\Models\FrAgendaAutorizacoesAutorizadas;
use App\Models\FrAgendaEnquete;
use App\Models\FrAgendaEnqueteRespostas;
use App\Models\FrAgendaEnqueteTurmas;
use App\Models\FrTurma;
use App\Models\User;
use DB;
use Storage;
use Intervention\Image\Facades\Image;

class EnqueteService {
    public function __construct()
    {
        $this->guardName = whatGuard();
    }

    public function scopoQueryEnquetesResponsavelApi($lista){
        $lista->join('fr_agenda_enquetes_turmas', 'enquete_id', 'fr_agenda_enquetes.id')
                ->join('users','users.id','fr_agenda_enquetes_turmas.aluno_id')
                ->selectRaw('distinct fr_agenda_enquetes.*, users.id as aluno_id, users.name as aluno_name, users.nome_completo as aluno_nome_completo, users.avatar_social, users.img_perfil, fr_agenda_enquetes_turmas.turma_id');

        $resp = User::with(['alunosDoResponsavel'=>function($q){
            $q->selectRaw('users.id');
        }])->find(auth()->user()->getRawOriginal('id'));

        $lista = $lista->where(function($q) use($resp){

            foreach($resp->alunosDoResponsavel as $r) {
                $q->orWhere(function ($query) use ($r){
                    $query->where('fr_agenda_enquetes_turmas.aluno_id',$r->id)
                        ->where('fr_agenda_enquetes_turmas.escola_id',$r->pivot->escola_id)
                        ->where('fr_agenda_enquetes_turmas.instituicao_id',$r->pivot->instituicao_id);
                });
            }
        });
        return $lista;
    }


    private function scopoQueryEnquetes($dados = null){
        $lista = FrAgendaEnquete::with('usuario');
        if(auth()->user()->permissao != 'R'){
            $lista->where('fr_agenda_enquetes.instituicao_id',auth()->user()->instituicao_id);
            if(auth()->user()->permissao != 'I') {
                $lista->where('fr_agenda_enquetes.escola_id', auth()->user()->escola_id);
            }
        }else{
            if($this->guardName == 'api') {
                $this->scopoQueryEnquetesResponsavelApi($lista);
            }
            else{
                return false;
            }
        }
        $lista->with(['alunos'=>function($q){
                $q->groupBy('enquete_id')->selectRaw('count(aluno_id) as qtd, enquete_id');
            }])
            ->with(['respondidos'=>function($q){
                $q->groupBy('enquete_id')->selectRaw('count(aluno_id) as qtd, enquete_id');
            }]);
        if($dados){
            if(isset($dados['nome']) && $dados['nome']!= ''){
                $lista->where(function($q) use($dados){
                    $q->orWhere('titulo','like','%'.$dados['nome'].'%')
                        ->orWhere('id',$dados['nome']);
                });
            }
            if(isset($dados['publicado']) && $dados['publicado']!= ''){
                $lista->where('publicado', $dados['publicado']);
            }
        }
        if(auth()->user()->permissao == 'P'){
            $lista->where('publicado', 1);
        }
        return $lista;
    }

    private function scopoQueryPermissaoEnquetes($query, $permissao){
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

    public function lista($dados){

        $lista = $this->scopoQueryEnquetes($dados);

        if($this->guardName == 'api') {
            ///// se for para pegar o ultimo id cadastrado para a API
            if(isset($dados['ultimo']) && $dados['ultimo'] == 1){
                return $lista->selectRaw('max(fr_agenda_enquetes.id) as ultimo')
                    ->first();
            }
            elseif(auth()->user()->permissao== 'R'){
                return $lista->orderBy('updated_at','DESC')
                    ->paginate(20);
            }

        }

        return $lista->selectRaw('distinct fr_agenda_enquetes.*')
            ->orderBy('updated_at','DESC')
            ->paginate(20);

    }

    public function excluir($id){
        DB::beginTransaction();
        try
        {
            $enquete = $this->scopoQueryEnquetes();
            $enquete = $this->scopoQueryPermissaoEnquetes($enquete, auth()->user()->permissao);
            $enquete = $enquete->where('publicado',0)
                ->selectRaw('distinct fr_agenda_enquetes.*')
                ->find($id);
            if($enquete) {
                $enquete->alunos()->detach();
                if ($enquete->imagem != ''){
                    $caminhoStorage = config('app.frStorage') . 'agenda/enquetes/' . auth()->user()->id . '/' . $enquete->id;
                    Storage::disk()->deleteDirectory($caminhoStorage);
                }
                $enquete->delete();
                DB::commit();
                return true;
            }else{
                DB::rollback();
                return false;
            }
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
            $comunicado = $this->scopoQueryEnquetes();
            $comunicado = $this->scopoQueryPermissaoEnquetes($comunicado, auth()->user()->permissao);
            $comunicado = $comunicado->where('publicado',0)
                ->selectRaw('distinct fr_agenda_enquetes.*')
                ->find($id);
            if($comunicado){
                $comunicado->update(['publicado'=>1]);
                DB::commit();
                $this->notificacaoMobile($comunicado);
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
            'titulo' => 'INspira Agenda - Enquetes e Pesquisa',
            'corpo' => $documento->titulo,
            'tipo' => 'agenda',
        ];
        $notificacao = new PushNotificationService();
        $notificacao->addNotificacaoEnquete($dados);
    }

    public function getEditar($id){
        try {
            $enquete = $this->scopoQueryEnquetes();
            $enquete = $this->scopoQueryPermissaoEnquetes($enquete, auth()->user()->permissao);
            $enquete = $enquete->where('publicado', 0)
                ->selectRaw('distinct fr_agenda_enquetes.*')
                ->find($id);
            $turmas = FrAgendaEnqueteTurmas::where('enquete_id', $enquete->id)->get();

            $enquete->turmas = $turmas;
            return $enquete;
        }
        catch (\Exception $e)
        {
            return false;
        }


    }

    public function inserir($dados, $file)
    {
        DB::beginTransaction();
        try
        {
            $dados['user_id'] = auth()->user()->id;
            $dados['instituicao_id'] = auth()->user()->instituicao_id;
            $dados['escola_id'] = auth()->user()->escola_id;
            $dados['permissao_usuario'] = auth()->user()->permissao;
            $enquete = new FrAgendaEnquete($dados);
            $enquete->save();
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
            if($file != '') {
                $img = [
                    'imagem' => $this->gravaImagem($file, $enquete->id),
                ];
                $enquete->update($img);
            }
            $enquete->alunos()->attach($alunos);
            DB::commit();
            if($enquete->publicado == 1){
                $this->notificacaoMobile($enquete);
            }
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e);
            return false;
        }
    }

    public function update($dados,$file)
    {
        DB::beginTransaction();
        try
        {
            $enquete = $this->scopoQueryEnquetes();
            $enquete = $this->scopoQueryPermissaoEnquetes($enquete, auth()->user()->permissao);
            $enquete = $enquete->where('publicado',0)
                ->selectRaw('distinct fr_agenda_enquetes.*')
                ->find($dados['id']);

            /// se tiver imagem
            $imagem = false;
            if($dados['arquivo_novo'] == 1) {
                $imagem = null;
                if($file != ''){
                    $imagem = $this->gravaImagem($file, $enquete->id);
                }
                $dados['imagem'] = $imagem;
            }elseif($file != ''){
                $imagem = $this->gravaImagem($file, $enquete->id);
                $dados['imagem'] = $imagem;
            }

            ///apaga storage
            if( $imagem !== false && $enquete->imagem != '' ){
                $caminhoStorage = config('app.frStorage') .'agenda/enquetes/' .auth()->user()->id.'/'.$enquete->id;
                Storage::disk()->deleteDirectory($caminhoStorage);
            }
            $enquete->update($dados);
            $enquete->alunos()->detach();

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
            $enquete->alunos()->attach($alunos);
            DB::commit();
            if($enquete->publicado == 1){
                $this->notificacaoMobile($enquete);
            }
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    private function gravaImagem($fileRequest,$idEnquete){
        try {
            $fileName = uniqid() . '.webp';
            $img = Image::make($fileRequest);
            $img->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('webp', 90);
            $resource = $img->stream()->detach();
            $caminhoStorage = config('app.frStorage') .'agenda/enquetes/' .auth()->user()->id.'/'.$idEnquete.'/'.$fileName;
            Storage::disk()->put($caminhoStorage, $resource);
            return $fileName;
        }
        catch (\Exception $e)
        {
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
                $q->join('fr_agenda_enquetes_turmas',function($join){
                    $join->on('fr_agenda_enquetes_turmas.aluno_id','=','fr_turma_aluno.aluno_id')
                        ->on('fr_turma_aluno.turma_id','=','fr_agenda_enquetes_turmas.turma_id');
                })
                    ->whereIn('users.id',$alunos);
                if(isset($dados['enquete'])){
                    $q->where('enquete_id', $dados['enquete']);
                }
            }])
            ->whereIn('fr_turmas.id',$turmas)
            ->orderBy('ciclos.id')
            ->orderBy('ciclo_etapas.id')
            ->orderBy('fr_turmas.titulo')
            ->selectRaw('fr_turmas.*, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo')
            ->get();
    }

    public function getExibir($idEnquete, $ativo = null){
        $enquete = $this->scopoQueryEnquetes();
        if($ativo == 1){
            $enquete->where('fr_agenda_enquetes.publicado',1);
        }
        $enquete = $enquete->selectRaw('distinct fr_agenda_enquetes.*')
            ->with('usuario')
            ->find($idEnquete);

        return $enquete;
    }

    public function responder($dados){
        DB::beginTransaction();
        try
        {
            $dados['responsavel_id'] = auth()->user()->id;
            $dados['enquete_id'] = $dados['id'];
            $temResposta = FrAgendaEnqueteRespostas::where('turma_id', $dados['turma_id'])
                ->where('aluno_id', $dados['aluno_id'])
                ->where('enquete_id', $dados['enquete_id'])
                ->with('responsavel')
                ->first();
            if($temResposta){
                DB::rollback();
                    return 'Já foi respondido por '.$temResposta->responsavel->nome;
            }
            else{
                $autorizado = new FrAgendaEnqueteRespostas($dados);
                $autorizado->save();
            }
            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            dd($e);
            return false;
        }
    }

    public function getRecebidos($enqueteId, $request=null){
        $recebidos = FrAgendaEnquete::join('fr_agenda_enquetes_turmas', 'fr_agenda_enquetes_turmas.enquete_id','fr_agenda_enquetes.id')
            ->join('fr_turmas','fr_turmas.id', 'fr_agenda_enquetes_turmas.turma_id')
            ->join('ciclo_etapas','fr_turmas.ciclo_etapa_id','ciclo_etapas.id')
            ->join('ciclos','ciclo_etapas.ciclo_id','ciclos.id')
            ->join('escolas','escolas.id', 'fr_turmas.escola_id')
            ->join('users','users.id', 'fr_agenda_enquetes_turmas.aluno_id')
            ->leftJoin('fr_agenda_enquetes_respostas', function($j){
                $j->on('fr_agenda_enquetes_respostas.enquete_id', 'fr_agenda_enquetes.id')
                    ->on('fr_agenda_enquetes_respostas.turma_id', 'fr_turmas.id')
                    ->on('fr_agenda_enquetes_respostas.aluno_id', 'users.id');
            })
            ->leftJoin('users as responsavel', 'fr_agenda_enquetes_respostas.responsavel_id', 'responsavel.id')
            ->where('fr_agenda_enquetes_turmas.enquete_id',$enqueteId)
            ->orderBy('escolas.titulo')
            ->orderBy('ciclos.id')
            ->orderBy('ciclo_etapas.id')
            ->orderBy('fr_turmas.titulo')
            ->orderBy('users.nome_completo')
            ->selectRaw('fr_agenda_enquetes_respostas.*, escolas.titulo as escola, fr_turmas.titulo as turma, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo, users.nome_completo as aluno_nome_completo, users.name as aluno_nome, responsavel.nome_completo as resp_nome_completo, responsavel.name as resp_nome');
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
                $recebidos->whereNotNull('fr_agenda_enquetes_respostas.resposta');
            }
            if($request['tipo'] == 2){
                $recebidos->whereNull('fr_agenda_enquetes_respostas.resposta');
            }
        }

        return $recebidos->paginate(20);
    }

    public function getRespondidos($enqueteId)
    {
        $respostas = FrAgendaEnqueteRespostas::where('enquete_id',$enqueteId)
                    ->groupBy('resposta')
                    ->selectRaw('count(resposta) as qtd, resposta')
                    ->get();
        $vetResposta = [];
        $maior = 0;
        foreach ($respostas as $r){
            $vetResposta[$r->resposta] = $r->qtd;
            if($r->qtd>$maior)
            {
                $maior = $r->qtd;
            }
        }
        $vetResposta['maior'] = $maior;
        return $vetResposta;

    }
}
