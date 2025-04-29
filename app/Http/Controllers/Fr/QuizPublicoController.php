<?php

namespace App\Http\Controllers\Fr;

use App\Models\FrQuiz;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Fr\QuizService;

class QuizPublicoController extends Controller
{
    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    public function publico($quizId)
    {

        $quiz = FrQuiz::where('public_id',$quizId)->first();
        return redirect('quiz/exibir?q='.$quiz->id);

/*        $retorno = $this->quizService->getExibir($quizId,true);
        if($retorno)
        {
            $view = [
                'quiz'=>$retorno,
                'frame'=> 0,
                'perguntaId'=> '',
                'f'=> 0,
            ];
                return view('fr/quiz/exibir/exibir_com_menu',$view);

        }else{
            return 'Quiz não encontrado.';
        }
*/
    }

}
