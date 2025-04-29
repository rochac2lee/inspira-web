<?php

namespace App\Http\Controllers\Fr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Fr\QuestaoService;
use App\Services\Fr\AvaliacaoService;

class AvaliacaoController extends Controller
{
	public function __construct(QuestaoService $questaoService, AvaliacaoService $avaliacaoService)
    {
        $this->middleware('auth');
        /*
        $this->middleware(function ($request, $next) {
            if(auth()->user()->permissao != 'A' )
            {
                return redirect('/');
            }
            return $next($request);
        });
        */
        $this->questaoService = $questaoService;
        $this->avaliacaoService = $avaliacaoService;
        $this->dificuldade = [
            '0' => 'Fácil',
            '1' => 'Médio',
            '2' => 'Difícil',
        ];

    }

    public function index(Request $request){
        $view = [
            'dados' => $this->avaliacaoService->avaliacoesAlunos($request),
        ];
        return view('fr/avaliacao/lista_aluno',$view);
    }

    public function avaliar(Request $request){
	    $dados = $this->avaliacaoService->getAvaliacaoAlunos($request->input('a'),1);
	    if($dados !== false){
            $view = [
                'avaliacao' => $dados['avaliacao'],
                'questoes' => $dados['questoes'],
            ];
            return view('fr/avaliacao/exibir/avaliacao',$view);
        }
	    else{
	        return redirect('avaliacao')->with('erro','Não foi possível localizar a avaliação.');
        }

    }

    public function logGeral(Request $request){
        $retorno = $this->avaliacaoService->addLogGeral($request->all());
        if($retorno){
            return response()->json( 'correto', 200 );
        }
        else{
            return response()->json( 'erro', 400 );
        }
    }

    public function logAtividade(Request $request){
        $retorno = $this->avaliacaoService->addLogAtividade($request->all());
        if($retorno){
            return response()->json( 'correto', 200 );
        }
        else{
            return response()->json( 'erro', 400 );
        }
    }

    public function finalizar(Request $request){
        $dados = $this->avaliacaoService->finalizar($request->all());
        if($dados !== false){

            return redirect('avaliacao?finalizado=1')->with('certo','Avaliação finalizada.');
        }
        else{
            return redirect('avaliacao?finalizado=1')->with('erro','Não foi possível finalizar a avaliação.');
        }

    }

    public function resultado($avaliacaoId){
	    $dados = $this->avaliacaoService->getResultadoAluno($avaliacaoId);
	    if($dados !== false){
            $view = [
                'avaliacao' => $dados['avaliacao'],
                'dados' => $dados['placar'],
            ];
            return view('fr/avaliacao/relatorio/relatorio_totalizado',$view);
        }
	    else{
            return back()->with('erro','Não foi possível localizar a avaliação.');
        }

    }
}
