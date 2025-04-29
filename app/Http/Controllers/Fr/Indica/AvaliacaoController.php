<?php

namespace App\Http\Controllers\Fr\Indica;

use App\Models\Indica\AvaliacaoLogGeral;
use App\Models\Indica\AvaliacaoTempo;
use App\Models\Indica\AvaliacaoTentativas;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Fr\Indica\QuestaoService;
use App\Services\Fr\Indica\AvaliacaoService;

class AvaliacaoController extends Controller
{
	public function __construct(QuestaoService $questaoService, AvaliacaoService $avaliacaoService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if(auth()->user()->permissao != 'A' )
            {
                return redirect('/');
            }
            return $next($request);
        });
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
        return view('fr/indica/avaliacao/lista_aluno',$view);
    }

    public function avaliar(Request $request){
	    $dados = $this->avaliacaoService->getAvaliacaoAlunos($request->input('a'),true, true);
	    if($dados !== false){

            return view('fr/indica/avaliacao/exibir/avaliacao',$dados);
        }
	    else{
	        return redirect('indica/avaliacao')->with('erro','Não foi possível localizar a avaliação.');
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

    public function logFechouJanela(Request $request){
        AvaliacaoTentativas::create([
            'indica_avaliacao_id'   => $request->input('avaliacao_id'),
            'user_id'               => auth()->user()->id,
            'instituicao_id'        => auth()->user()->instituicao_id,
            'escola_id'             => auth()->user()->escola_id,
            'iniciou'               => 0,
        ]);

        $ultimo = AvaliacaoLogGeral::orderBy('id','desc')
            ->where('indica_avaliacao_id', $request->input('avaliacao_id'))
            ->where('user_id', auth()->user()->id)
            ->first();

        $ultimoSaiu = AvaliacaoTentativas::orderBy('id','desc')
            ->where('indica_avaliacao_id', $request->input('avaliacao_id'))
            ->where('user_id', auth()->user()->id)
            ->where('iniciou',0)
            ->first();

        $tempo = $ultimo->created_at->diffInSeconds($ultimoSaiu->created_at);

        $novo = $ultimo->replicate();
        $novo->tempo_inativo = 0;
        $novo->tempo_ativo = $tempo;
        $novo->created_at = Carbon::now();
        $novo->updated_at = Carbon::now();
        $novo->save();

    }

    public function finalizar(Request $request){
        $dados = $this->avaliacaoService->finalizar($request->all());
        if($dados !== false){

            return redirect('indica/avaliacao?finalizado=1')->with('certo','Avaliação finalizada.');
        }
        else{
            return redirect('indica/avaliacao?finalizado=1')->with('erro','Não foi possível finalizar a avaliação.');
        }

    }

    public function resultado($avaliacaoId){
	    $dados = $this->avaliacaoService->getResultadoAluno($avaliacaoId);
	    if($dados !== false){
            $view = [
                'avaliacao' => $dados['avaliacao'],
                'dados' => $dados['placar'],
            ];
            return view('fr/indica/avaliacao/relatorio/relatorio_totalizado',$view);
        }
	    else{
            return back()->with('erro','Não foi possível localizar a avaliação.');
        }

    }
}
