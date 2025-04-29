<?php
namespace App\Services\Fr\Agenda;
use App\Models\Escola;
use App\Models\FrAgendaComunicadoImagens;
use App\Models\FrAgendaComunicados;
use App\Models\FrAgendaComunicadosTurmas;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ComunicadoService {

    public function __construct()
    {
        $this->guardName = whatGuard();
    }
    private function alunosDoResponsavel(){

        $resp = User::with(['alunosDoResponsavel'=>function($q){
            $q->selectRaw('users.id');
        }])
            ->find(auth()->user()->getRawOriginal('id'));

        return $resp->alunosDoResponsavel;
    }

    private function scopoQueryComunicadosResponsavel(){
        return FrAgendaComunicados::join('fr_agenda_comunicados_fr_turmas', 'agenda_comunicado_id', 'fr_agenda_comunicados.id')
            ->where(function($query){
                $alunos = $this->alunosDoResponsavel();
                foreach ($alunos as $a) {
                    $query->orWhere(function ($qq) use($a){
                        $qq->where('fr_agenda_comunicados_fr_turmas.instituicao_id', $a->pivot->instituicao_id)
                            ->where(function ($q) use($a){
                                $q->orWhere('fr_agenda_comunicados_fr_turmas.escola_id', $a->pivot->escola_id)
                                    ->orWhere('fr_agenda_comunicados_fr_turmas.escola_id', 0);
                            });
                    });
                }
            });
    }

    private function scopoQueryComunicados($dados = null){
        if(auth()->user()->permissao == 'R'){
            $lista = $this->scopoQueryComunicadosResponsavel();
        }else {
            $lista = FrAgendaComunicados::join('fr_agenda_comunicados_fr_turmas', 'agenda_comunicado_id', 'fr_agenda_comunicados.id')
                ->where('fr_agenda_comunicados_fr_turmas.instituicao_id', auth()->user()->instituicao_id);
            if (auth()->user()->permissao != 'I') {
                $lista->where(function ($q) {
                    $q->orWhere('fr_agenda_comunicados_fr_turmas.escola_id', auth()->user()->escola_id)
                        ->orWhere('fr_agenda_comunicados_fr_turmas.escola_id', 0);
                });
            }
        }
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

    public function lista($dados){
        $lista = $this->scopoQueryComunicados($dados);
        if($this->guardName == 'api'){
            ///// se for para pegar o ultimo id cadastrado para a API
            if(isset($dados['ultimo']) && $dados['ultimo'] == 1){
                return $lista->selectRaw('max(fr_agenda_comunicados.id) as ultimo')
                    ->orderBy('updated_at','DESC')
                    ->first();
            }
            else{
                $lista = $lista->with('imagens');
            }
        }
        return $lista->selectRaw('distinct fr_agenda_comunicados.*')
            ->with('usuario')
            ->with('escolas')
            ->orderBy('updated_at','DESC')
            ->paginate(20);
    }

    public function excluir($id){
        DB::beginTransaction();
        try
        {
            $comunicado = $this->scopoQueryComunicados();
            $comunicado = $this->scopoQueryPermissaoComunicados($comunicado, auth()->user()->permissao);
            $comunicado = $comunicado->where('publicado',0)
                                    ->with('imagens')
                                    ->selectRaw('distinct fr_agenda_comunicados.*')
                                    ->find($id);
            if($comunicado){
                $comunicado->turmas()->detach();
                foreach ($comunicado->imagens as $i){
                    $this->apagarImg($comunicado->id, $i->id);
                }
                $caminhoStorage = config('app.frStorage') .'agenda/comunicados/' .auth()->user()->id.'/'.$comunicado->id;
                Storage::disk()->deleteDirectory($caminhoStorage);
                $comunicado->delete();
                DB::commit();
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

    public function publicar($id){
        DB::beginTransaction();
        try
        {
            $comunicado = $this->scopoQueryComunicados();
            $comunicado = $this->scopoQueryPermissaoComunicados($comunicado, auth()->user()->permissao);
            $comunicado = $comunicado->where('publicado',0)
                            ->selectRaw('distinct fr_agenda_comunicados.*')
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

    private function notificacaoMobile($comunicado){
        $dados = [
            'id' => $comunicado->id,
            'titulo' => 'INspira Agenda - Comunicados',
            'corpo' => $comunicado->titulo,
            'tipo' => 'agenda',
        ];
        $notificacao = new PushNotificationService();
        $notificacao->addNotificacaoComunicado($dados);
    }

    public function getEditar($id){
        try {
            $comunicado = $this->scopoQueryComunicados();
            $comunicado = $this->scopoQueryPermissaoComunicados($comunicado, auth()->user()->permissao);
            $comunicado = $comunicado->where('publicado', 0)
                ->with('imagens')
                ->selectRaw('distinct fr_agenda_comunicados.*')
                ->find($id);
            $turmas = FrAgendaComunicadosTurmas::where('agenda_comunicado_id', $comunicado->id)->get();

            $comunicado->turmas = $turmas;
            return $comunicado;
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
            $dados['permissao_usuario'] = auth()->user()->permissao;
            $comunicado = new FrAgendaComunicados($dados);
            $comunicado->save();
            $turmas = [];
            foreach($dados['escola'] as $e){
                foreach($dados['turma'][$e] as $t){
                    $turma = [
                        'turma_id' => $t,
                        'escola_id' => $e,
                        'instituicao_id' => auth()->user()->instituicao_id,
                    ];
                    $turmas[] = $turma;
                }
            }
            if($file != '') {
                $img = [
                    'caminho' => $this->gravaImagem($file, $comunicado->id),
                ];
                $img = new FrAgendaComunicadoImagens($img);
                $comunicado->imagens()->save($img);
            }
            $comunicado->turmas()->attach($turmas);
            DB::commit();

            if($comunicado->publicado==1){
                $this->notificacaoMobile($comunicado);
            }
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    public function update($dados)
    {
        DB::beginTransaction();
        try
        {
            $comunicado = $this->scopoQueryComunicados();
            $comunicado = $this->scopoQueryPermissaoComunicados($comunicado, auth()->user()->permissao);
            $comunicado = $comunicado->where('publicado',0)
                ->selectRaw('distinct fr_agenda_comunicados.*')
                ->find($dados['id']);

            $comunicado->update($dados);
            $comunicado->turmas()->detach();
            $turmas = [];

            foreach($dados['escola'] as $e){
                foreach($dados['turma'][$e] as $t){
                    $turma = [
                        'turma_id' => $t,
                        'escola_id' => $e,
                        'instituicao_id' => auth()->user()->instituicao_id,
                    ];
                    $turmas[] = $turma;
                }
            }
            $comunicado->turmas()->attach($turmas);
            DB::commit();

            if($comunicado->publicado==1){
                $this->notificacaoMobile($comunicado);
            }
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return false;
        }
    }

    private function gravaImagem($fileRequest,$idComunicado){
        try {
            $fileName = uniqid() . '.webp';
            $img = Image::make($fileRequest);
            $img->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('webp', 90);
            $resource = $img->stream()->detach();
            $caminhoStorage = config('app.frStorage') .'agenda/comunicados/' .auth()->user()->id.'/'.$idComunicado.'/'.$fileName;
            Storage::disk()->put($caminhoStorage, $resource);
            return $fileName;
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    public function uploadImagensAjax($idComunicado, $fileRequest){
        try {
            $comunicado = $this->scopoQueryComunicados();
            $comunicado = $comunicado->where('publicado', 0)
                ->selectRaw('distinct fr_agenda_comunicados.*')
                ->find($idComunicado);
            if($comunicado){
                $img = [
                    'caminho' => $this->gravaImagem($fileRequest,$comunicado->id),
                ];
                if($img['caminho']){
                    $img = new FrAgendaComunicadoImagens($img);
                    $img = $comunicado->imagens()->save($img);
                    return [
                        'id' => $img->id,
                        'caminho' => $img->caminho,
                    ];
                }else{
                    return false;
                }
            }
            else{
                return false;
            }
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    public function excluirImagem($idComunicado, $idImagem){
        try {
            $comunicado = $this->scopoQueryComunicados();
            $comunicado = $this->scopoQueryPermissaoComunicados($comunicado, auth()->user()->permissao);
            $comunicado = $comunicado->where('publicado', 0)
                ->selectRaw('distinct fr_agenda_comunicados.*')
                ->find($idComunicado);
            if($comunicado) {
                return $this->apagarImg($idComunicado, $idImagem);
            }
            else{
                return false;
            }
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    private function apagarImg($idComunicado, $idImagem){
        $img = FrAgendaComunicadoImagens::find($idImagem);
        $caminhoStorage = config('app.frStorage') .'agenda/comunicados/' .auth()->user()->id.'/'.$idComunicado.'/'.$img->caminho;
        Storage::disk()->delete($caminhoStorage);
        return $img->delete();
    }

    public function getEscolasTurmasSelecionados($dados){
        $escolas = [];
        $turmas = [];
        foreach($dados['escola'] as $e){
            foreach($dados['turma'][$e] as $t){
                $escolas[$e]= $e;
                $turmas[$t] = $t;
            }
        }
        return Escola::with(['turmas'=>function($q) use($turmas){
                        $q->whereIn('fr_turmas.id',$turmas);
                    }])
            ->whereIn('id',$escolas)
            ->orderBy('titulo')
            ->get();
    }

    public function ordernarImagem($idComunicado, $dados){
        DB::beginTransaction();
        try
        {
            $comunicado = $this->scopoQueryComunicados();
            $comunicado = $this->scopoQueryPermissaoComunicados($comunicado, auth()->user()->permissao);
            $comunicado = $comunicado->where('publicado', 0)
                ->selectRaw('distinct fr_agenda_comunicados.*')
                ->find($idComunicado);
            if($comunicado) {
                $i = 0;
                foreach ($dados['ordem'] as $d){
                    $img = FrAgendaComunicadoImagens::where('comunicado_id',$idComunicado)->find($d);
                    $img->update(['ordem'=>$i]);
                    $i++;
                }
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

    public function getExibir($idComunicado){
        $comunicado = $this->scopoQueryComunicados();
        $comunicado = $comunicado->selectRaw('distinct fr_agenda_comunicados.*')
            ->with('imagens')
            ->with('usuario')
            ->find($idComunicado);

        return $comunicado;
    }
}
