<?php

namespace App\Http\Controllers\Fr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Fr\QuizService;

class QuizController extends Controller
{
    public function __construct(QuizService $quizService)
    {
        $this->middleware('auth');
        $this->quizService = $quizService;
    }

    public function colecao()
    {
        $retorno = $this->quizService->listaColecao();
        $view = [
            'dados'=>$retorno,
        ];
        return view('fr/quiz/exibir/colecao',$view);
    }

    public function index(Request $request)
    {
    	$retorno = $this->quizService->exibir($request);
    	if( (!isset($retorno->respondido) || !is_int($retorno->respondido) ) && auth()->user()->permissao=='R'){

            return back()->with('erro', 'Seu perfil é de responsável ou familiar.');
        }
    	if($retorno)
    	{
    		$view = [
    			'quiz'=>$retorno,
                'perguntaId'=>$request->input('p'),
                'f'=>$request->input('f'), /// quando estiver finalizado recebe 1
                'frame'=>$request->input('frame'),
    		];
    		if($request->input('frame') == 1){
                return view('fr/quiz/exibir/exibir_sem_menu',$view);
            }
    		else
            {
                return view('fr/quiz/exibir/exibir_com_menu',$view);
            }

    	}elseif($request->input('frame') != 1){
            return back();
        }

    }

    public function publico($quizId)
    {
        $retorno = $this->quizService->getExibir($quizId,true);
        if($retorno)
        {
            $view = [
                'quiz'=>$retorno,
                'frame'=> 1,
                'perguntaId'=> '',
                'f'=> 0,
            ];
                return view('fr/quiz/exibir/exibir_sem_menu',$view);

        }else{
            return 'Quiz não encontrado.';
        }
    }

    public function verificaResposta(Request $request)
    {
        $retorno = $this->quizService->verificaResposta($request);
        if($retorno){
            return response()->json( $retorno, 200 );
        }
        else
        {
            return response()->json( $retorno, 400 );
        }
    }

    public function finalizado(Request $request)
    {

        $dados = $this->quizService->finalizado($request->input('q'));
        $total = $this->quizService->totalizadorPlacar($request->input('q'));
        if($dados)
        {
            $quiz = json_decode($dados->quiz_respondido);
            $view = [
                'quiz'=>$quiz,
                'dados'=>$dados,
                'total'=>$total,
                'frame'=>$request->input('frame'),
            ];
            if($request->input('frame')!=1)
            {
                return view('fr/quiz/finalizado_com_menu',$view);
            }
            else
            {
                return view('fr/quiz/finalizado_sem_menu',$view);
            }
        }
        else
        {
            return back()->with('erro','Não foi possível finalizar seu Quiz');
        }

    }

    public function listar(Request $request)
    {
        if(auth()->user()->permissao != 'A' && auth()->user()->permissao != 'R')
        {
            return back();
        }
        $retorno = $this->quizService->listaAluno($request);
        $view = [
            'dados'=>$retorno,
        ];
        return view('fr/quiz/lista_aluno',$view);
    }
}
