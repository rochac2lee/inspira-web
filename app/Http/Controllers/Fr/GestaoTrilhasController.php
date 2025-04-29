<?php

namespace App\Http\Controllers\Fr;

use App\Http\Requests\Fr\TrilhaRequest;
use App\Models\Disciplina;
use App\Models\Ead\Avaliacao;
use App\Models\Instituicao;
use App\Models\InstituicaoTipo;
use App\Services\Fr\RoteiroService;
use App\Services\Fr\TrilhasService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GestaoTrilhasController extends Controller
{
	public function __construct(TrilhasService $trilhasService, RoteiroService $roteiroService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if(Auth::user()->permissao == 'A' )
            {
                return back();
            }
            return $next($request);
        });
        $this->trilhasService = $trilhasService;
        $this->roteiroService = $roteiroService;
    }

    public function index(Request $request)
    {
        $busca = $request->all();
        $view = [
            'trilhas' => $this->trilhasService->getLista(20,$busca),
            'cicloEtapa' => $this->trilhasService->cicloEtapa(),
            'disciplina' => Disciplina::orderBy('titulo')->get(),
        ];
       return view('fr/trilhas/index', $view);
    }

    public function form()
    {
        $view = [
            'tipoInstituicao' => InstituicaoTipo::orderBy('titulo')->get(),
            'instituicao' => Instituicao::orderBy('titulo')->get(),
            'ciclo' => $this->roteiroService->cicloEtapa(),
            'disciplinas' => Disciplina::orderBy('titulo')->get(),
            'roteirosSelecionados' => '',
            'roteirosSelecionadosId' =>  [],
            'avaliacao' => Avaliacao::where('publicado',1)->orderBy('titulo')->get(),
        ];
        return view('fr/trilhas/form', $view);
    }

    public function add(TrilhaRequest $request)
    {
        $retorno = $this->trilhasService->inserir($request);

        if($retorno===true){
            return redirect('/gestao/trilhass')->with('certo', 'Trilha cadastrada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cadastrar trilha.');
        }
    }

    public function editar($id){
        $dados = $this->trilhasService->get($id);
        $view = [
            'dados' => $this->roteiroService->getLista(1000,['id' => $dados->cursos->pluck('id')->toArray()]),
        ];
        $roteirosSelecionados = view('fr.trilhas.lista_roteiro_trilha',$view)->render();
        $view = [
            'tipoInstituicao' => InstituicaoTipo::orderBy('titulo')->get(),
            'instituicao'   => Instituicao::orderBy('titulo')->get(),
            'ciclo'         => $this->roteiroService->cicloEtapa(),
            'disciplinas'   => Disciplina::orderBy('titulo')->get(),
            'roteiros'      => $this->roteiroService->getLista(50,[]),
            'dados'         => $dados,
            'roteirosSelecionados'  => $roteirosSelecionados,
            'roteirosSelecionadosId' =>  $dados->cursos->pluck('id')->toArray(),
            'avaliacao' => Avaliacao::where('publicado',1)->orderBy('titulo')->get(),
        ];
        return view('fr/trilhas/form', $view);
    }

    public function update(TrilhaRequest $request)
    {
        $retorno = $this->trilhasService->update($request);

        if($retorno===true){
            return redirect('/gestao/trilhass')->with('certo', 'Trilha editada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar trilha.');
        }
    }

    public function excluir($id)
    {
        $retorno = $this->trilhasService->excluir($id);

        if($retorno===true){
            return redirect('/gestao/trilhass')->with('certo', 'Trilha excluída.');
        }
        else{
            return redirect('/gestao/trilhass')->with('erro', 'Erro ao tentar excluir trilha.');
        }
    }

    public function publicar($id, Request $request)
    {
        $retorno = $this->trilhasService->publicar($id, $request->all());
        if($retorno===true){
            return back()->with('certo', 'Trilha publicada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar publicar trilha.');
        }
    }

    public function duplicar($id, Request $request)
    {
        $retorno = $this->trilhasService->duplicar($id, $request->all());

        if($retorno===true){
            return back()->with('certo', 'Trilha duplicada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar duplicar trilha.');
        }
    }

    public function periodoMatricula($id, Request $request)
    {
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }
        $retorno = $this->trilhasService->periodoMatricula($id, $request->all());

        if($retorno===true){
            return back()->with('certo', 'Periodo de matricula alterado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar alterar periodo de matrícula.');
        }
    }
    public function getPeriodoMatricula($id)
    {
        if(auth()->user()->permissao != 'Z')
        {
            return back();
        }
        $retorno = $this->trilhasService->get($id,['ead'=>1]);
        $dados = [
            'ead_matricula_inicio' => dataBR($retorno->ead_matricula_inicio),
            'ead_matricula_fim' => dataBR($retorno->ead_matricula_fim),
        ];

        return response()->json( $dados, 200 );
    }


    public function relatorio($id){
        $dados = $this->trilhasService->get($id);
        $relatorio = $this->trilhasService->getRoteiroConteudoRelatorio($dados->id);
        $view = [
            'dados' => $dados,
            'relatorio' => $relatorio,
            'cursados' => $this->trilhasService->getRealizadosTrilhasRoteiros($dados->id),
            'percCursados' => $this->trilhasService->getPercAlunosTrilha($id, $relatorio->matriculas->pluck('id')),
        ];
        return view('fr/trilhas/relatorio', $view);
    }

    public function getInteracao(Request $request){
        $dados = $this->trilhasService->getInteracao($request->all());
        return response()->json( $dados, 200 );
    }
}
