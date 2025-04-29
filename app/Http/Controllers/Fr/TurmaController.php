<?php

namespace App\Http\Controllers\Fr;

use App\Models\Escola;
use App\Http\Requests\Fr\TurmaRequest;
use App\Services\Fr\EscolaService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Services\Fr\TurmaService;
class TurmaController extends Controller
{
    public function __construct( TurmaService $turmaService, EscolaService $escolaService)
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if(Auth::user()->permissao != 'Z' && Auth::user()->permissao != 'I' && Auth::user()->permissao != 'C')
            {
                return back();
            }
            return $next($request);
        });
        $this->turmaService = $turmaService;
        $this->escolaService = $escolaService;
    }

    public function index($idEscola, Request $request)
    {
        if (!$this->escolaEhDaInstituicao($idEscola)){
            return back();
        }
    	$view = [
                'escola'=> Escola::find($idEscola),
                'turmas'=> $this->turmaService->lista($idEscola,$request->all()),
                'cicloEtapa'=> $this->turmaService->cicloEtapa(),
            ];
        return view('fr.gestao.turma.lista',$view);
    }

    public function nova($idEscola)
    {
        if (!$this->escolaEhDaInstituicao($idEscola)){
            return back();
        }
        $view = [
            'escola'    => Escola::find($idEscola),
            'cicloEtapa'=> $this->turmaService->cicloEtapa(),
        ];
        return view('fr.gestao.turma.form',$view);
    }

    public function add(TurmaRequest $request)
    {
        if (!$this->escolaEhDaInstituicao($request->input('escola_id'))){
            return back();
        }
        $retorno = $this->turmaService->inserir($request->all());

        if($retorno===true){
            return redirect('gestao/escola/'.$request->input('escola_id').'/turmas')->with('certo', 'Turma cadastrada.');
        }
        else{
            return redirect('gestao/escola/'.$request->input('escola_id').'/turmas')->with('erro', 'Erro ao tentar cadastrar turma. '.$retorno);
        }
    }

    public function formEditar($idEscola,$idTurma)
    {
        if (!$this->escolaEhDaInstituicao($idEscola)){
            return back();
        }
        $view = [
            'escola'    => Escola::find($idEscola),
            'cicloEtapa'=> $this->turmaService->cicloEtapa(),
            'dados' => $this->turmaService->get($idTurma),
        ];
        return view('fr/gestao/turma/form',$view);
    }

    public function editar(TurmaRequest $request)
    {
        if (!$this->escolaEhDaInstituicao($request->input('escola_id'))){
            return back();
        }
        $retorno = $this->turmaService->editar($request->all());

        if($retorno===true){
            return redirect('gestao/escola/'.$request->input('escola_id').'/turmas')->with('certo', 'Turma editada.');
        }
        else{
            return redirect('gestao/escola/'.$request->input('escola_id').'/turmas')->with('erro', 'Erro ao tentar editar turma.');
        }
    }

    public function excluir($id)
    {
        $instituicaoId = null;
        if(auth()->user()->permissao == 'I' || auth()->user()->permissao == 'C'){
            $instituicaoId = auth()->user()->instituicao_id;
        }
        $retorno = $this->turmaService->excluir($id,$instituicaoId);

        if($retorno){
            return back()->with('certo', 'Turma excluída.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir turma.');
        }
    }

    public function getProfessoresTabela(Request $request)
    {
        $professores = $this->turmaService->professorParaAdicionar($request->all());
        $view = [
            'professores'    => $professores,
            'selecionados'  => $request->input('professores'),
        ];
        $retorno =  view('fr/gestao/turma/tabelaProfessor',$view)->render();
        return response()->json( $retorno, 200 );
    }

    public function getAlunosTabela(Request $request)
    {
        $alunos = $this->turmaService->alunoParaAdicionar($request);
        $view = [
            'alunos'    => $alunos,
            'selecionados'  => $request->input('alunos'),
        ];
        $retorno =  view('fr/gestao/turma/tabelaAluno',$view)->render();
        return response()->json( $retorno, 200 );
    }

    public function getProfessorAluno(Request $request)
    {
        $tipo = 'professor';
        if($request->input('tipo') == 'A'){
            $tipo = 'aluno';
        }
        $selecionados = $this->turmaService->alunoProfessorSelecionados($request->input('selecionados'));
        $view = [
            'selecionados'  => $selecionados,
            'tipo'  => $tipo,
        ];
        $retorno =  view('fr/gestao/turma/alunoProfessorSelecionados',$view)->render();
        return response()->json( $retorno, 200 );
    }

    private function escolaEhDaInstituicao($idEscola){
        if(auth()->user()->permissao == 'I'){
            $pode = $this->escolaService->escolaEhDaInstituicao($idEscola);
            if(!$pode){
                return false;
            }
        }elseif(auth()->user()->permissao == 'C'){
            if($idEscola != auth()->user()->escola_id){
                return false;
            }
        }
        return true;
    }
}
