<?php

namespace App\Http\Controllers\Fr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Fr\QuizRequest;
use App\Http\Requests\Fr\QuizPerguntaTipo1Request;
use App\Http\Requests\Fr\QuizPerguntaTipo2Request;
use App\Http\Requests\Fr\QuizPerguntaTipo3Request;
use App\Http\Requests\Fr\QuizPerguntaTipo4Request;

use App\Services\Fr\QuizService;
use App\Services\Fr\QuizRelatorioService;
use App\Models\Disciplina;
use App\Models\ColecaoLivros;

use Auth;
use Illuminate\Support\Facades\Storage;

class GestaoQuizController extends Controller
{
    public function __construct(QuizService $quizService, QuizRelatorioService $quizRelatorioService)
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if(Auth::user()->permissao == 'A' || Auth::user()->permissao == 'R')
            {
                return back();
            }
            return $next($request);
        });

        $this->quizService = $quizService;
        $this->quizRelatorioService = $quizRelatorioService;
    }

    /*
    /* GESTÃO DO QUIZ
    /*
    */

    public function lista(Request $request)
    {
        $view=[
            'dados'		 => $this->quizService->getLista(21,$request->all()),
            'cicloEtapa' => $this->quizService->cicloEtapa(),
            'disciplina' => Disciplina::orderBy('titulo')->get(),
            'disciplinaFiltro' => $this->quizService->listaColecao(),
            'colecao' => ColecaoLivros::where('tipo',104)->orderBy('ordem')->get(),
        ];
        return view('fr/quiz/lista',$view);
    }

    public function add(QuizRequest $request)
    {
        $retorno = $this->quizService->inserir($request);

        if($retorno===true){
            return redirect('/gestao/quiz?componente='.$request->input('disciplina_id'))->with('certo', 'Quiz cadastrado.');
        }
        else{
            return redirect('/gestao/quiz?componente='.$request->input('disciplina_id'))->with('erro', 'Erro ao tentar cadastrar quiz.');
        }
    }

    public function excluir($id)
    {
        session(['UrlPreviousExcluirQuiz' => url()->previous()]);
        $verifica = $this->quizService->temParticipantes($id);
        if($verifica){
            return redirect('/gestao/quiz/confirmar/excluir/'.$id);
        }
        $retorno = $this->quizService->excluir($id);

        if($retorno){
            return back()->with('certo', 'Quiz excluído.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir quiz.');
        }
    }

    public function confirmarExcluir($id)
    {
        $view = [
            'dados' => $this->quizService->getForm($id,auth()->user()->id),
        ];
        return view('fr/quiz/confirma_exclusao',$view);
    }
    public function excluirTotal(Request $request)
    {
        $retorno = $this->quizService->excluir($request->input('id'),1);

        if($retorno){
            return redirect(session('UrlPreviousExcluirQuiz'))->with('certo', 'Quiz excluído.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir quiz.');
        }
    }

    public function getAjax(Request $request)
    {
        $retorno = $this->quizService->getForm($request->input('id'),Auth::user()->id);
        if($retorno){
            return response()->json( $retorno, 200 );
        }
        else
        {
            return response()->json( $retorno, 400 );
        }
    }

    public function editar(QuizRequest $request)
    {

        $retorno = $this->quizService->editar($request);

        if($retorno===true){
            return back()->with('certo', 'Quiz editado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar quiz.');
        }
    }

    public function publicar($quizId, $publicado)
    {

        $retorno = $this->quizService->publicar($quizId, $publicado);

        if($retorno===true){
            return back()->with('certo', 'Publicação do quiz alterada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar alterar publicação do quiz.');
        }
    }

    public function duplicar($quizId)
    {
        $retorno = $this->quizService->duplicar($quizId);

        if($retorno===true){
            return back()->with('certo', 'Quiz duplicado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar duplicar quiz.');
        }
    }

    public function ordemPergunta(Request $request)
    {
        $retorno = $this->quizService->ordemPergunta($request);
        if($retorno){
            return response()->json( 1, 200 );
        }
        else
        {
            return response()->json( 0, 400 );
        }
    }

    /*
    /* GESTÃO DAS PERGUNTAS E RESPOSTAS
    /*
    */

    public function listaPerguntas($id)
    {
        $view=[
            'quiz'      => $this->quizService->get($id),
            'perguntas' => $this->quizService->getPerguntas($id),
        ];

        return view('fr/quiz/perguntas/lista',$view);
    }

    public function excluirPergunta($id)
    {
        $retorno = $this->quizService->excluirPergunta($id);

        if($retorno){
            return back()->with('certo', 'Pergunta excluída.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir pergunta.');
        }
    }

    public function duplicarPergunta($perguntaId)
    {
        $retorno = $this->quizService->copyPergunta($perguntaId);

        if($retorno===true){
            return back()->with('certo', 'Pergunta duplicada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar duplicar pergunta.');
        }
    }

    public function formPerguntaTipo1($id)
    {
        $view=[
            'quiz' => $this->quizService->get($id),
        ];

        return view('fr/quiz/perguntas/tela_form_pergunta_1',$view);
    }

    public function getPerguntaTipo1($id, $perguntaId)
    {

        $view=[
            'quiz' => $this->quizService->get($id),
            'dados'=> $this->quizService->getPerguntaTipo1Form($perguntaId),
        ];

        return view('fr/quiz/perguntas/tela_form_pergunta_1',$view);
    }

    public function addPerguntaTipo1(QuizPerguntaTipo1Request $request)
    {
        $retorno = $this->quizService->inserirPerguntaTipo1($request);

        if($retorno===true){
            return redirect('/gestao/quiz/'.$request->input('quiz_id').'/perguntas')->with('certo', 'Pergunta cadastrada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cadastrar pergunta.');
        }
    }
    /*
        public function getAjaxPerguntaTipo1(Request $request)
        {
            $retorno = $this->quizService->getPerguntaTipo1Form($request->input('id'));
            if($retorno){
                return response()->json( $retorno, 200 );
            }
            else
            {
                return response()->json( $retorno, 400 );
            }
        }
    */
    public function editarPerguntaTipo1(QuizPerguntaTipo1Request $request)
    {
        $retorno = $this->quizService->editarPerguntaTipo1($request);

        if($retorno===true){
            return redirect('/gestao/quiz/'.$request->input('quiz_id').'/perguntas')->with('certo', 'Pergunta editada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar pergunta.');
        }
    }

    public function formPerguntaTipo2($id)
    {
        $view=[
            'quiz' => $this->quizService->get($id),
        ];

        return view('fr/quiz/perguntas/tela_form_pergunta_2',$view);
    }

    public function addPerguntaTipo2(QuizPerguntaTipo2Request $request)
    {
        $retorno = $this->quizService->inserirPerguntaTipo2($request);

        if($retorno===true){
            return redirect('/gestao/quiz/'.$request->input('quiz_id').'/perguntas')->with('certo', 'Pergunta cadastrada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cadastrar pergunta.');
        }
    }

    public function getPerguntaTipo2($id, $perguntaId)
    {

        $view=[
            'quiz' => $this->quizService->get($id),
            'dados'=> $this->quizService->getPerguntaTipo2Form($perguntaId),
        ];

        return view('fr/quiz/perguntas/tela_form_pergunta_2',$view);
    }
    /*
        public function getAjaxPerguntaTipo2(Request $request)
        {
            $retorno = $this->quizService->getPerguntaTipo2Form($request->input('id'));
            if($retorno){
                return response()->json( $retorno, 200 );
            }
            else
            {
                return response()->json( $retorno, 400 );
            }
        }
    */
    public function editarPerguntaTipo2(QuizPerguntaTipo2Request $request)
    {
        $retorno = $this->quizService->editarPerguntaTipo2($request);

        if($retorno===true){
            return redirect('/gestao/quiz/'.$request->input('quiz_id').'/perguntas')->with('certo', 'Pergunta editada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar pergunta.');
        }
    }

    public function formPerguntaTipo3($id)
    {
        $view=[
            'quiz' => $this->quizService->get($id),
        ];

        return view('fr/quiz/perguntas/tela_form_pergunta_3',$view);
    }

    public function addPerguntaTipo3(QuizPerguntaTipo3Request $request)
    {
        $retorno = $this->quizService->inserirPerguntaTipo3($request);

        if($retorno===true){
            return redirect('/gestao/quiz/'.$request->input('quiz_id').'/perguntas')->with('certo', 'Pergunta cadastrada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cadastrar pergunta.');
        }
    }

    public function getPerguntaTipo3($id, $perguntaId)
    {

        $view=[
            'quiz' => $this->quizService->get($id),
            'dados'=> $this->quizService->getPerguntaTipo3Form($perguntaId),
        ];

        return view('fr/quiz/perguntas/tela_form_pergunta_3',$view);
    }
    /*
        public function getAjaxPerguntaTipo3(Request $request)
        {
            $retorno = $this->quizService->getPerguntaTipo3Form($request->input('id'));
            if($retorno){
                return response()->json( $retorno, 200 );
            }
            else
            {
                return response()->json( $retorno, 400 );
            }
        }
    */
    public function editarPerguntaTipo3(QuizPerguntaTipo3Request $request)
    {
        $retorno = $this->quizService->editarPerguntaTipo3($request);

        if($retorno===true){
            return redirect('/gestao/quiz/'.$request->input('quiz_id').'/perguntas')->with('certo', 'Pergunta editada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar pergunta.');
        }
    }

    public function formPerguntaTipo4($id,$idPergunta =null)
    {
        $view=[
            'quiz' => $this->quizService->get($id),
            'idPergunta' => $idPergunta,
        ];

        return view('fr/quiz/perguntas/tela_form_pergunta_4',$view);
    }

    public function addPerguntaTipo4(QuizPerguntaTipo4Request $request)
    {

        $retorno = $this->quizService->inserirPerguntaTipo4($request);

        if($retorno===true){
            return redirect('gestao/quiz/'.$request->input('quiz_id').'/perguntas')->with('certo', 'Pergunta cadastrada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cadastrar pergunta.');
        }
    }

    public function getAjaxPerguntaTipo4(Request $request)
    {
        $retorno = $this->quizService->getPerguntaTipo4Form($request->input('id'));
        if($retorno){
            return response()->json( $retorno, 200 );
        }
        else
        {
            return response()->json( $retorno, 400 );
        }
    }

    public function editarPerguntaTipo4(QuizPerguntaTipo4Request $request)
    {
        $retorno = $this->quizService->editarPerguntaTipo4($request);

        if($retorno===true){
            return redirect('gestao/quiz/'.$request->input('quiz_id').'/perguntas')->with('certo', 'Pergunta editada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar pergunta.');
        }
    }

    public function gravarAudioTemporario(Request $request)
    {

        $audio = Storage::putFile(config('app.frStorage').'quiz/audioTemporario', $request->file('audio'));
        return response()->json( $audio, 200 );

    }

    public function limparPlacar($id)
    {
        $this->quizService->limparPlacar($id);
        return response()->json( 1, 200 );
    }

    public function relatorio(Request $request, $id)
    {
        $this->quizService->limparPlacar($id);
        $quiz = $this->quizService->get($id,auth()->user()->id);

        if($quiz){
            $view = [
                'quiz' => $quiz,
                'dados'=> $this->quizRelatorioService->relatorio($request->input('tipo'),$id),
                'naoFinalizados' => $this->quizRelatorioService->naoFinalizados($id),
            ];
            return view('fr/quiz/relatorio/index',$view);
        }else{
            return back();
        }
    }

    public function relatorioPergunta(Request $request)
    {
        $retorno = $this->quizService->exibir($request);
        $relatorio = $this->quizRelatorioService->relatorioPergunta($request);
        if($retorno)
        {
            $view = [
                'quiz'      => $retorno,
                'perguntaId'=> $request->input('p'),
                'frame'     => 1,
                'relatorio' => $relatorio,
            ];
            return view('fr/quiz/exibir/exibir_sem_menu',$view);
        }

    }
}
