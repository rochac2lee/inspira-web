<?php

namespace App\Http\Controllers\Fr\AvaliacaoEAD;

use App\Models\Altitude\TrilhasMatriculasUsuario;
use App\Models\Trilha;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Fr\AvaliacaoEAD\QuestaoService;
use App\Services\Fr\AvaliacaoEAD\AvaliacaoService;

class AvaliacaoController extends Controller
{
	public function __construct(QuestaoService $questaoService, AvaliacaoService $avaliacaoService)
    {
        $this->middleware('auth');
        $this->questaoService = $questaoService;
        $this->avaliacaoService = $avaliacaoService;
        $this->dificuldade = [
            '0' => 'Fácil',
            '1' => 'Médio',
            '2' => 'Difícil',
        ];

    }

    public function avaliar(Request $request){
	    $dados = $this->avaliacaoService->getAvaliacaoAlunos($request->input('a'), $request->input('trilha'),true);

	    if($dados !== false){
            TrilhasMatriculasUsuario::where('user_id',auth()->user()->id)->where('trilha_id', $request['trilha'])->increment('tentativas_avaliacao');

            $view = [
                'avaliacao' => $dados['avaliacao'],
                'questoes' => $dados['questoes'],
                'trilhaId' => $request->input('trilha'),
            ];
            return view('fr/trilhas/avaliacao',$view);
        }
	    else{
	        return back()->with('erro','Não foi possível localizar a avaliação.');
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

            return redirect('ead/matriculado/'.$request->input('trilha_id').'/roteiro?finalizado=1')->with('certo','Avaliação finalizada.');
        }
        else{
            return redirect('ead/matriculado/'.$request->input('trilha_id').'/roteiro?finalizado=1')->with('erro','Não foi possível finalizar a avaliação.');
        }
    }

    public function resultado($avaliacaoId, $trilhaId){
        $trilha = Trilha::where('avaliacao_id',$avaliacaoId)->find($trilhaId);
        if(!$trilha){
            return back()->with('erro','Não foi possível localizar a trilha.');
        }
	    $dados = $this->avaliacaoService->getResultadoAluno($avaliacaoId, $trilhaId);
	    if($dados !== false){
            $view = [
                'avaliacao' => $dados['avaliacao'],
                'dados' => $dados['placar'],
            ];
            return view('fr/trilhas/relatorio_avaliacao_totalizado',$view);
        }
	    else{
            return back()->with('erro','Não foi possível localizar a avaliação.');
        }

    }
}
