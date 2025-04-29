<?php

namespace App\Http\Controllers\Fr\Agenda;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fr\Agenda\CanaisAtendimentoRequest;
use App\Models\Escola;
use App\Models\FrTurma;
use App\Services\Fr\Agenda\CanaisAtendimentoService;
use App\Services\Fr\EscolaService;
use Illuminate\Http\Request;

class CanaisAtendimentoController extends Controller
{
    public function __construct( CanaisAtendimentoService $canaisAtendimentoService, EscolaService $escolaService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->permissao != 'I' && auth()->user()->permissao != 'C') {
                return redirect('/');
            }
            return $next($request);
        });

        $this->canaisAtendimentoService = $canaisAtendimentoService;
        $this->escolaService = $escolaService;
    }

    public function index(Request $request){
        $escola = Escola::where('instituicao_id',auth()->user()->instituicao_id)->orderBy('titulo')->get();
        $view = [
            'escolas' => $escola,
            'dados' => $this->canaisAtendimentoService->lista($request->all(), count($escola)),
        ];
        return view('fr.agenda.canais_atendimento.lista',$view);
    }

    public function novo(){
        $view = [];
        return view('fr.agenda.canais_atendimento.form', $view);
    }

    public function add(CanaisAtendimentoRequest $request){
        $retorno = $this->canaisAtendimentoService->inserir($request->all());

        if($retorno===true){
            return redirect('/gestao/agenda/canais-atendimento')->with('certo', 'Canal de atendimento cadastrado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cadastrar enquete.');
        }
    }

    public function excluir($idEnquete){
        $retorno = $this->canaisAtendimentoService->excluir($idEnquete);

        if($retorno===true){
            return redirect('/gestao/agenda/canais-atendimento')->with('certo', 'Canal de atendimento excluído.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir enquete.');
        }
    }

    public function publicar($idEnquete,$tipo){
        $retorno = $this->canaisAtendimentoService->publicar($idEnquete,$tipo);

        if($retorno===true){
            return redirect('/gestao/agenda/canais-atendimento')->with('certo', 'Canal de atendimento publicado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar publicar enquete.');
        }
    }

    public function editar($idEnquete){
        $dados = $this->canaisAtendimentoService->getEditar($idEnquete);
        if($dados){
            $view = [
                'dados'=> $dados,
            ];
            return view('fr.agenda.canais_atendimento.form', $view);
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar enquete.');
        }
    }

    public function update(CanaisAtendimentoRequest $request){
        $retorno = $this->canaisAtendimentoService->update($request->all());

        if($retorno===true){
            return redirect('/gestao/agenda/canais-atendimento')->with('certo', 'Canal de atendimento editado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar enquete.');
        }
    }

    public function getEscolas(Request $request){
        $dados = $this->escolaService->getLista(10, $request->all());
        $view = [
            'dados' => $dados,
            'escola' => $request->input('escola'),
            'turma' => $request->input('turma'),
        ];
        $retorno =  view('fr.agenda.canais_atendimento.tabelaEscola',$view)->render();
        return response()->json( $retorno, 200 );
    }

    public function getEscolasSelecionados(Request $request){
        $dados = $this->canaisAtendimentoService->getEscolasSelecionados($request->all());
        $view = [
            'dados' => $dados,
        ];
        $retorno =  view('fr.agenda.canais_atendimento.listaEscolaSelecionados',$view)->render();
        return response()->json( $retorno, 200 );
    }

    public function ordenar(Request $request){
        $retorno = $this->canaisAtendimentoService->ordem($request->all());
        if($retorno){
            return response()->json( true, 200 );
        }else{
            return response()->json( false, 500 );
        }
    }
}
