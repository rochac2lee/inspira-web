<?php
namespace App\Services\Fr\Agenda;
use App\Models\Escola;
use App\Models\FrAgendaRegistroRotina;
use App\Models\FrAgendaRegistrosTurmaProfessor;
use App\Models\FrAgendaRegistrosTurmaProfessorAluno;
use App\Models\FrAgendaRegistroTurmaProfessorAluno;
use App\Models\FrTurma;
use App\Models\User;
use App\Services\Fr\TurmaService;
use DB;

class RegistroService
{

    private function alunosDoResponsavel($dados = null){
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

    public function getUltimoResgistroResponsavel(){
        $alunos = $this->alunosDoResponsavel();
        return FrAgendaRegistrosTurmaProfessorAluno::whereIn('aluno_id',$alunos)
            ->selectRaw('max(fr_agenda_registros_turma_professor_aluno.id) as ultimo')
            ->first();
    }

    public function getRegistroResposavelApi($dados = null){
        $registro = FrAgendaRegistrosTurmaProfessor::whereHas('alunos',function($q) use($dados){
                $q->where('aluno_id', $dados['aluno_id']);
            })
            ->with(['registroData'=>function($query)use($dados){
                $query->join('fr_agenda_registros_rotinas', 'fr_agenda_registros_rotinas.id', 'fr_agenda_registros_turma_professor.registro_id')
                ->join('fr_agenda_registros_turma_professor_aluno', 'fr_agenda_registros_turma_professor_aluno.registro_turma_id', 'fr_agenda_registros_turma_professor.id')
                ->where('aluno_id', $dados['aluno_id'])
                ->selectRaw('fr_agenda_registros_turma_professor.*, fr_agenda_registros_rotinas.titulo, fr_agenda_registros_rotinas.imagem,  fr_agenda_registros_rotinas.instituicao_id, marcado, texto, fr_agenda_registros_turma_professor_aluno.updated_at as cadastrado_em');
            }])
            ->groupBy('data')
            ->orderBy('data','DESC')
            ->paginate(20);
        return $registro;
    }

    public function getTurmas($dados = null)
    {
        $sel = [];
        $turmaService = new TurmaService();
        if (auth()->user()->permissao != 'I') {
            $escolaId = auth()->user()->escola_id;
        }else{
            $escolaId = $dados['escola_id'];
        }
        if(isset($dados['professor_id']) && $dados['professor_id'] != ''){
            $sel['professor_id'] = $dados['professor_id'];
        }
        return $turmaService->lista($escolaId, $sel, 300);
    }

    public function getRegistro($dados)
    {
        if(auth()->user()->permissao == 'P'){
            $dados['professor_id'] = auth()->user()->id;
            $dados['escola_id'] = auth()->user()->escola_id;
        }if(auth()->user()->permissao == 'C'){
            $dados['escola_id'] = auth()->user()->escola_id;
        }

        try {
            $turma = FrTurma::join('ciclo_etapas', 'fr_turmas.ciclo_etapa_id', 'ciclo_etapas.id')
                ->join('ciclos', 'ciclo_etapas.ciclo_id', 'ciclos.id')
                ->where('escola_id', $dados['escola_id'])
                ->with('alunos')
                ->with('escola')
                ->selectRaw('fr_turmas.*, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo')
                ->find($dados['turma_id']);

            $rotinas = FrAgendaRegistroRotina::where('fr_agenda_registros_turma_professor.professor_id', $dados['professor_id'])
                ->where('fr_agenda_registros_turma_professor.turma_id', $turma->id)
                ->where('fr_agenda_registros_turma_professor.data', $dados['data'])
                ->join('fr_agenda_registros_turma_professor', 'fr_agenda_registros_rotinas.id', 'fr_agenda_registros_turma_professor.registro_id')
                ->withTrashed()
                ->selectRaw('fr_agenda_registros_rotinas.*')
                ->orderByRaw('ISNULL(ordem), ordem ASC')
                ->orderBy('titulo')
                ->get();
            if (count($rotinas) == 0) {
                $rotinas = FrAgendaRegistroRotina::where('instituicao_id', auth()->user()->instituicao_id)
                    ->where('rotina', $turma->rotina_id)
                    ->where('ativo', 1)
                    ->orderByRaw('ISNULL(ordem), ordem ASC')
                    ->orderBy('titulo')
                    ->get();
                $novaRotina = [];
                foreach ($rotinas as $r) {
                    $novaRotina[] = [
                        'registro_id' => $r->id,
                        'turma_id' => $turma->id,
                        'professor_id' => $dados['professor_id'],
                        'data' => $dados['data'],
                    ];
                }
                FrAgendaRegistrosTurmaProfessor::insert($novaRotina);
                $dadosRegistros = [];
            }
            else{
                $dadosRegistros = $this->dadosRegistros($dados);
            }
            return [
                'turma'         => $turma,
                'rotina'        => $rotinas,
                'dadosRegistros' => $dadosRegistros,
            ];
        } catch (\Exception $e) {
            return [];
        }

    }

    private function dadosRegistros($dados)
    {
        if(auth()->user()->permissao == 'P'){
            $dados['professor_id'] = auth()->user()->id;
        }
        $registros = FrAgendaRegistrosTurmaProfessor::where('turma_id', $dados['turma_id'])
                                        ->where('professor_id', $dados['professor_id'])
                                        ->where('data', $dados['data'])
                                        ->join('fr_agenda_registros_turma_professor_aluno', 'fr_agenda_registros_turma_professor_aluno.registro_turma_id', 'fr_agenda_registros_turma_professor.id')
                                        ->selectRaw('fr_agenda_registros_turma_professor.registro_id, fr_agenda_registros_turma_professor_aluno.aluno_id, fr_agenda_registros_turma_professor_aluno.marcado, fr_agenda_registros_turma_professor_aluno.texto')
                                        ->get();
        $retorno = [];
        foreach($registros as $r){
            $retorno[$r->registro_id][$r->aluno_id] = $r;
        }
        return $retorno;
    }

    public function salvar($dados)
    {
        DB::beginTransaction();
        try {
            if (auth()->user()->permissao == 'P') {
                $dados['professor_id'] = auth()->user()->id;
            }
            $this->deletaRegistrosTurmaProfessorNaoUsados($dados);
            if (isset($dados['registro'])) {
                foreach ($dados['registro'] as $r) {
                    $registro = FrAgendaRegistrosTurmaProfessor::where('turma_id', $dados['turma_id'])
                        ->where('professor_id', $dados['professor_id'])
                        ->where('data', $dados['data'])
                        ->where('registro_id', $r)
                        ->first();

                    foreach ($dados['texto'][$r] as $aId => $a) {
                        $where = [
                            'registro_turma_id' => $registro->id,
                            'aluno_id' => $aId,
                        ];
                        $marcado = 0;
                        if (isset($dados['marcado'][$r][$aId]) && $dados['marcado'][$r][$aId] == 1) {
                            $marcado = 1;
                        }
                        $update = [
                            'marcado' => $marcado,
                            'texto' => $a,
                        ];
                        FrAgendaRegistroTurmaProfessorAluno::updateOrCreate($where, $update);
                    }
                }
            }
            DB::commit();
            $this->notificacaoMobile($dados['turma_id']);
            return true;
        }
        catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return false;
        }
    }

    private function notificacaoMobile($turmaId){
        $dados = [
            'id' => $turmaId,
            'titulo' => 'INspira Agenda - Agenda e Registro',
            'corpo' => 'Você tem uma nova interação',
            'tipo' => 'agenda',
        ];
        $notificacao = new PushNotificationService();
        $notificacao->addNotificacaoRegistro($dados);
    }

    private function deletaRegistrosTurmaProfessorNaoUsados($dados){
        $registro = FrAgendaRegistrosTurmaProfessor::where('turma_id', $dados['turma_id'])
            ->where('professor_id', $dados['professor_id'])
            ->where('data', $dados['data']);
            if(isset($dados['registro']) && count($dados['registro'])>0) {
                $registro->whereNotIn('registro_id', $dados['registro']);
            }
        $registro = $registro->get();

        foreach($registro as $r){
            FrAgendaRegistroTurmaProfessorAluno::where('registro_turma_id', $r->id)->delete();
        }
    }

    public function getTurmasProf($dados)
    {
        $turmas = $this->getTurmas($dados);
        $retorno = [];
        foreach($turmas as $t){
            $obj = new \stdClass();
            $obj->text = $t->ciclo.'/'.$t->ciclo_etapa.' - '.$t->titulo.' / '.$t->turno;
            $obj->value = $t->id;
            if(isset($dados['turma_id']) && $dados['turma_id'] == $t->id){
                $obj->selected = true;
            }
            $retorno[] = $obj;
        }
        return $retorno;
    }

    public function getProf($dados)
    {
        $dadosProf =[
            'escola_id' => $dados['escola_id'],
            'sem_page'  =>1,
        ];
        $turmaService = new TurmaService();
        $prof = $turmaService->professorParaAdicionar($dadosProf);
        $retorno = [];
        $obj = new \stdClass();
        $obj->text = 'Selecione';
        $obj->value = null;
        $retorno[] = $obj;
        foreach($prof as $t){
            $obj = new \stdClass();
            $obj->text = $t->nome;
            $obj->value = $t->id;
            if(isset($dados['professor_id']) && $dados['professor_id'] == $t->id){
                $obj->selected = true;
            }
            $retorno[] = $obj;
        }
        return $retorno;
    }
}
