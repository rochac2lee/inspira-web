<?php
namespace App\Services\Fr;
use App\Models\Ciclo;
use App\Models\Escola;
use App\Models\FrTurma;
use App\Models\User;
use DB;
use Illuminate\Database\Eloquent\Model;

class TurmaService {

    public function lista($idEscola, $dados, $page = 20)
    {
        $turma = FrTurma::join('ciclo_etapas','fr_turmas.ciclo_etapa_id','ciclo_etapas.id')
                        ->join('ciclos','ciclo_etapas.ciclo_id','ciclos.id');

        if(auth()->user()->permissao == 'P' || isset($dados['professor_id'])){
            $turma = $turma->join('fr_turma_professor','fr_turma_professor.turma_id', 'fr_turmas.id');
            if(auth()->user()->permissao == 'P') {
                $turma = $turma->where('fr_turma_professor.professor_id', auth()->user()->id)
                    ->with(['alunos']);
            }
            if(isset($dados['professor_id'])){
                $turma = $turma->where('fr_turma_professor.professor_id', $dados['professor_id']);
            }
        }else{
            $turma = $turma->with(['professores','alunos']);
        }
        if(!is_array($idEscola)){
            $idEscola = [$idEscola];
        }
        $turma = $turma->whereIn('escola_id',$idEscola)
                        ->selectRaw('fr_turmas.*, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo')
                        ->orderBy('ciclos.id')
                        ->orderBy('ciclo_etapas.id')
                        ->orderBy('fr_turmas.titulo');
        if(isset($dados['nome']) && $dados['nome']!= ''){
            $turma = $turma->where(function($q) use($dados){
                $q->orWhere('fr_turmas.titulo','like', '%'.$dados['nome'].'%')
                    ->orWhere('fr_turmas.id',$dados['nome']);
            });
        }

        if(isset($dados['ciclo_etapa_id']) && $dados['ciclo_etapa_id']!= ''){
            $turma = $turma->where('fr_turmas.ciclo_etapa_id', $dados['ciclo_etapa_id']);
        }
        if(isset($dados['sem_rotina'])){
            $turma = $turma->whereNull('fr_turmas.rotina_id');
        }
        if(isset($dados['rotina_id'])){
            $turma = $turma->where('fr_turmas.rotina_id',$dados['rotina_id']);
        }
        $turma = $turma->paginate($page);

        return $turma;
    }

    public function professorParaAdicionar($dados){
        $escolaId = $dados['escola_id'];
        $buscaNome = null;
        if(isset($dados['nome'])) {
            $buscaNome = $dados['nome'];
        }
        $professores =  User::leftJoin('user_permissao','users.id','user_permissao.user_id')
            ->where(function($q) use ($escolaId){
                $q->orWhere(function($qq) use ($escolaId){
                    $qq->where('users.permissao','P')
                        ->where('users.escola_id',$escolaId);
                });
                $q->orWhere(function($qq) use ($escolaId){
                    $qq->where('user_permissao.permissao','P')
                        ->where('user_permissao.escola_id',$escolaId);
                });
            })
            ->groupBy('users.id')
            ->orderBy('users.name')
            ->selectRaw('users.*');
        if($buscaNome != ''){
            $professores = $professores->where(function($q) use ($buscaNome){
                $q->orWhere('users.id',$buscaNome)
                    ->orWhere('users.nome_completo','like','%'.$buscaNome.'%')
                    ->orWhere('users.email','like','%'.$buscaNome.'%');
            });
        }
        if(isset($dados['sem_page'])){
            $professores = $professores->get();
        }else{
            $professores = $professores->paginate(5);
        }

        return $professores;
    }

    public function alunoParaAdicionar($request){
        $escolaId = $request->input('escola_id');
        $buscaNome = $request->input('nome');
        $alunos =  User::leftJoin('user_permissao','users.id','user_permissao.user_id')
            ->where(function($q) use ($escolaId){
                $q->orWhere(function($qq) use ($escolaId){
                    $qq->where('users.permissao','A')
                        ->where('users.escola_id',$escolaId);
                });
                $q->orWhere(function($qq) use ($escolaId){
                    $qq->where('user_permissao.permissao','A')
                        ->where('user_permissao.escola_id',$escolaId);
                });
            })
            ->whereNotExists(
                function($query) use($escolaId) {
                    $query->from('users as u')
                        ->where('permissao', 'P')
                        ->where('escola_id', $escolaId)
                        ->whereRaw('users.id = u.id');
                })
            ->whereNotExists(
                function($query) use($escolaId) {
                    $query->from('user_permissao as up')
                        ->where('permissao', 'P')
                        ->where('escola_id', $escolaId)
                        ->whereRaw('user_permissao.user_id = up.user_id');
                })
            ->groupBy('users.id')
            ->orderBy('users.name')
            ->selectRaw('users.*');
        if($buscaNome != ''){
            $alunos = $alunos->where(function($q) use ($buscaNome){
                $q->orWhere('users.id',$buscaNome)
                    ->orWhere('users.nome_completo','like','%'.$buscaNome.'%')
                    ->orWhere('users.email','like','%'.$buscaNome.'%');
            });
        }
        $alunos = $alunos->paginate(5);
        return $alunos;

    }

    public function inserir($dados)
    {
        DB::beginTransaction();
        try
        {
            $turma = new FrTurma($dados);
            $turma->save();

            $turma->professores()->attach($dados['professor']);
            $turma->alunos()->attach($dados['aluno']);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return $e;
        }
    }

    public function editar($dados)
    {
        DB::beginTransaction();
        try
        {
            $turma = FrTurma::find($dados['turma_id']);
            $turma->update($dados);
            $turma->professores()->sync($dados['professor']);
            $turma->alunos()->sync($dados['aluno']);

            DB::commit();
            return true;
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return $e;
        }
    }

    public function get($turmaId){
        return FrTurma::with(['professores','alunos'])->find($turmaId);
    }

    public function alunoProfessorSelecionados($selecionados){
        return User::whereIn('id',$selecionados)->get();
    }

    public function excluir($id, $instituicaoId = null)
    {
        $turma = FrTurma::find($id);
        if($instituicaoId){
            $escola = Escola::where('instituicao_id',$instituicaoId);
            if(auth()->user()->permissao == 'C'){
                $escola = $escola->where('id',auth()->user()->escola_id);
            }
            $escola = $escola->find($turma->escola_id);
            if(!$escola){
                return false;
            }
        }
        return $turma->delete();
    }

    public function cicloEtapa()
    {
        return Ciclo::join('ciclo_etapas','ciclos.id','ciclo_etapas.ciclo_id')
            ->where('ciclos.id','<>',5)
            ->where('ciclo_etapas.id','<>',1)
            ->where('ciclo_etapas.id','<>',2)
            ->where('ciclo_etapas.id','<>',3)
            ->where('ciclo_etapas.id','<>',21)
            ->where('ciclo_etapas.id','<>',22)
            ->orderBy('ciclos.id')
            ->orderBy('ciclo_etapas.id')
            ->selectRaw('ciclo_etapas.id, ciclos.id as ciclo_id, ciclos.titulo as ciclo, ciclo_etapas.titulo as ciclo_etapa')
            ->get();
    }

}
