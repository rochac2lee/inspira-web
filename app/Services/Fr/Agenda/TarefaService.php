<?php
namespace App\Services\Fr\Agenda;

use App\Models\FrAgendaTarefa;
use App\Models\FrTurma;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class TarefaService {

    public function __construct()
    {
        $this->guardName = whatGuard();
    }

    private function alunosDoResponsavel($dados){
        $alunos=[];
        if(isset($dados['aluno_id']) && $dados['aluno_id']!=''){
            $alunos[] = $dados['aluno_id'];
        }else{
            $resp = User::with(['alunosDoResponsavel'=>function($q){
                $q->selectRaw('users.id');
            }])
                ->find(auth()->user()->getRawOriginal('id'));
            $alunos = [];
            foreach($resp->alunosDoResponsavel as $r){
                $alunos[] = $r->id;
            }
        }
        return $alunos;
    }

    public function getTarefaResposavelApi($dados = null){
        if(auth()->user()->permissao == 'R') {
            $where = 'aluno_id';
            $dadosWhere = $this->alunosDoResponsavel($dados);
        }elseif(auth()->user()->permissao == 'I') {
            $where = 'instituicao_id';
            $dadosWhere = [auth()->user()->instituicao_id];
        }else{
            $where = 'escola_id';
            $dadosWhere = [auth()->user()->escola_id];
        }
        $tarefa = FrAgendaTarefa::whereHas('alunos',function($q) use($where, $dadosWhere){
                    $q->whereIn($where, $dadosWhere);
                })
                ->with(['tarefaData'=>function($query)use($where, $dadosWhere){
                    $query->whereHas('alunos',function($q) use($where, $dadosWhere){
                        $q->whereIn($where, $dadosWhere);
                    })->where('publicado',1);
                }]);
        if(auth()->user()->permissao == 'P'){
            $tarefa->where('professor_id',auth()->user()->id);
        }
        $tarefa->groupBy('data_entrega')
                ->where('publicado',1)
                ->orderBy('updated_at','DESC')
                ->paginate(20);
        return $tarefa;
    }

    public function scopoQueryTarefasResponsavelApi($tarefa,$dados){
        $alunos=[];
        if(isset($dados['aluno_id']) && $dados['aluno_id']!=''){
            $alunos[] = $dados['aluno_id'];
        }else{
            $resp = User::with(['alunosDoResponsavel'=>function($q){
                $q->selectRaw('users.id');
            }])
                ->find(auth()->user()->getRawOriginal('id'));
            $alunos = [];
            foreach($resp->alunosDoResponsavel as $r){
                $alunos[] = $r->id;
            }
        }

        $tarefa->whereHas('alunos',function($q) use($alunos){
                $q->whereIn('aluno_id', $alunos);
            });
        return $tarefa;
    }

    public function scopoQueryTarefas($dados = null){
        $tarefa = FrAgendaTarefa::with(['escola','professor','disciplina']);
        if(auth()->user()->permissao == 'I'){
            $tarefa->where('instituicao_id',auth()->user()->instituicao_id);
        }
        elseif(auth()->user()->permissao == 'C'){
            $tarefa->where('escola_id',auth()->user()->escola_id);
        }
        elseif(auth()->user()->permissao == 'R'){
            if($this->guardName == 'api') {
                $tarefa = $this->scopoQueryTarefasResponsavelApi($tarefa, $dados);
            }else{
                return false;
            }
        }
        else{
            $tarefa->where('escola_id',auth()->user()->escola_id)
                    ->where('professor_id',auth()->user()->id);
        }

        if($dados){
            if(isset($dados['nome']) && $dados['nome']!= ''){
                $tarefa->where(function($q) use($dados){
                    $q->orWhere('titulo','like','%'.$dados['nome'].'%')
                        ->orWhere('id',$dados['nome']);
                });
            }
            if(isset($dados['publicado']) && $dados['publicado']!= ''){
                $tarefa->where('publicado', $dados['publicado']);
            }
        }


        return $tarefa;
    }

    public function lista($dados = null){
        $tarefa = $this->scopoQueryTarefas($dados);
        if($this->guardName == 'api'){
            ///// se for para pegar o ultimo id cadastrado para a API
            if(isset($dados['ultimo']) && $dados['ultimo'] == 1){
                return $tarefa->selectRaw('max(fr_agenda_tarefa.id) as ultimo')
                    ->orderBy('updated_at','DESC')
                    ->first();
            }
        }
        return $tarefa->orderBy('updated_at','DESC')
            ->paginate(20);
    }

    public function inserir($dados, $file)
    {
        DB::beginTransaction();
        try
        {
            $dados['professor_id'] = auth()->user()->id;
            $dados['instituicao_id'] = auth()->user()->instituicao_id;
            $dados['escola_id'] = auth()->user()->escola_id;
            $tarefa = new FrAgendaTarefa($dados);
            $tarefa->save();
            if($file != '') {
                $arquivo = $this->gravaArquivo($file,$tarefa->id);
                if($arquivo != false){
                    $tarefa->update(['arquivo'=>$arquivo, 'nome_arquivo_original'=>$file->getClientOriginalName()]);
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
            $tarefa->alunos()->attach($alunos);
            DB::commit();
            if($tarefa->publicado == 1){
                $this->notificacaoMobile($tarefa);
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
            $tarefa = $this->scopoQueryTarefas();
            $tarefa = $tarefa->where('publicado',0)
                ->selectRaw('distinct fr_agenda_tarefa.*')
                ->find($id);
            if($tarefa){
                if($tarefa->arquivo != '') {
                    $caminhoStorage = config('app.frStorage') . 'agenda/tarefas/' . auth()->user()->id . '/' . $tarefa->id;
                    Storage::disk()->deleteDirectory($caminhoStorage);
                }
                $tarefa->alunos()->sync([]);
                $tarefa->delete();
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
            dd($e);
            return false;
        }
    }

    public function publicar($id){
        DB::beginTransaction();
        try
        {
            $tarefa = $this->scopoQueryTarefas();
            $tarefa = $tarefa->where('publicado',0)
                ->find($id);
            if($tarefa){
                $tarefa->update(['publicado'=>1]);
                DB::commit();
                $this->notificacaoMobile($tarefa);
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
            'titulo' => 'INspira Agenda - Tarefas e Atividades',
            'corpo' => $documento->titulo.' Entregar em '.$documento->data_entrega->format('d/m/Y'),
            'tipo' => 'agenda',
            'dono_tarefa' => $documento->professor_id,
        ];
        $notificacao = new PushNotificationService();
        $notificacao->addNotificacaoTarefa($dados);
    }

    private function gravaArquivo($fileRequest, $idTarefa){
        try {
            $vetTipo = ['webp', 'gif', 'png', 'bmp', 'jpg', 'jpeg', 'tif'];
            $ext = $fileRequest->getClientOriginalExtension();

            if(in_array( strtolower($ext), $vetTipo)){
                return $this->gravaImagem($fileRequest, $idTarefa);
            }
            else{
                $file = $fileRequest->store(
                    config('app.frStorage').'agenda/tarefas/' .auth()->user()->id.'/'.$idTarefa
                );
                $file = explode('/', $file);
                return $file[count($file)-1];
            }
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    private function gravaImagem($fileRequest, $idTarefa){
        $fileName = uniqid() . '.webp';
        $img = Image::make($fileRequest);
        $img->resize(500, 500, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('webp', 90);
        $resource = $img->stream()->detach();
        $caminhoStorage = config('app.frStorage') .'agenda/tarefas/' .auth()->user()->id.'/'.$idTarefa.'/'.$fileName;
        Storage::disk()->put($caminhoStorage, $resource);
        return $fileName;
    }

    public function get($idTarefa,$ativo=null){
        $tarefa = $this->scopoQueryTarefas();
        $tarefa->with('alunos')
                ->selectRaw('distinct fr_agenda_tarefa.*');
        if($ativo != null){
            $tarefa = $tarefa->where('publicado',$ativo);
        }
        return $tarefa->find($idTarefa);

    }

    public function update($dados,$file)
    {
        DB::beginTransaction();
        try
        {
            $tarefa = $this->get($dados['id'],0);
            if((isset($dados['arquivo_novo']) && $dados['arquivo_novo'] == 1) || $file != '')
            {
                if($tarefa->arquivo != '') {
                    $caminhoStorage = config('app.frStorage') . 'agenda/tarefas/' . auth()->user()->id . '/' . $tarefa->id.'/'.$tarefa->arquivo;
                    Storage::disk()->delete($caminhoStorage);
                }
                $dados['arquivo'] = '';
                $dados['nome_arquivo_original'] = '';
                if($file != '') {
                    $arquivo = $this->gravaArquivo($file,$tarefa->id);
                    if($arquivo != false){
                        $dados['arquivo'] = $arquivo;
                        $dados['nome_arquivo_original'] = $file->getClientOriginalName();
                    }
                }
            }
            $tarefa->update($dados);
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
            $tarefa->alunos()->sync($alunos);
            if($tarefa->publicado == 1){
                $this->notificacaoMobile($tarefa);
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
            ->with(['qtdAlunos','alunos'=>function($q) use($alunos,$dados){
                $q->join('fr_agenda_tarefa_alunos','fr_agenda_tarefa_alunos.aluno_id','fr_turma_aluno.aluno_id')
                    ->whereIn('users.id',$alunos);
                if(isset($dados['tarefa'])){
                    $q->where('tarefa_id', $dados['tarefa']);
                }
            }])
            ->whereIn('fr_turmas.id',$turmas)
            ->orderBy('ciclos.id')
            ->orderBy('ciclo_etapas.id')
            ->orderBy('fr_turmas.titulo')
            ->selectRaw('fr_turmas.*, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo')
            ->get();
    }
}
