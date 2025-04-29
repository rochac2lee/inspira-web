<?php

namespace App\Http\Controllers\Fr;

use App\Jobs\Questao\FullText;
use App\Models\FrQuestao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Http\Requests\Fr\QuestaoRequest;
use App\Services\Fr\QuestaoService;

use App\Models\Disciplina;
use App\Models\FrQuestaoFormato;
use App\Models\FrQuestaoSuporte;
use App\Models\FrQuestaoTema;
use App\Models\FrBncc;
use Illuminate\Support\Facades\Session;

class GestaoQuestaoController extends Controller
{
	public function __construct(QuestaoService $questaoService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if(Auth::user()->permissao == 'A' )
            {
                return back();
            }
            return $next($request);
        });
        $this->questaoService = $questaoService;
        $this->dificuldade = [
        	'0' => 'Fácil',
        	'1' => 'Médio',
        	'2' => 'Difícil',
        ];
    }
    public function minhasQuestoes(Request $request)
    {
    	$view = [
    		'dados'         => $this->questaoService->minhasQuestoes($request),
    		'dificuldade'   => $this->dificuldade,
            'disciplina'    => Disciplina::where('titulo','<>','Todos')->orderBy('titulo')->get(),
            'cicloEtapa'    => $this->questaoService->cicloEtapa(),
            'formato'       => FrQuestaoFormato::orderBy('ordem')->get(),
            'suporte'       => FrQuestaoSuporte::orderBy('ordem')->get(),
            'fonte'        => $this->questaoService->fonte(),
    	];
    	return view('fr/avaliacao/lista_questoes', $view);
    }

    public function mudaStatusMinhasQuestoes(Request $request)
    {

        $retorno = $this->questaoService->mudaStatusMinhasQuestoes($request);

        if($retorno){
            return response()->json( $retorno, 200 );
        }
        else
        {
            return response()->json( $retorno, 400 );
        }
    }

    public function formQuestoes()
    {
    	$view = [
            'disciplina' => Disciplina::where('titulo','<>','Todos')->orderBy('titulo')->get(),
            'suporte' => FrQuestaoSuporte::orderBy('ordem')->get(),
    		'formato' => FrQuestaoFormato::orderBy('ordem')->get(),
    		'cicloEtapa'=> $this->questaoService->cicloEtapa(),
    	];
    	return view('fr/avaliacao/form_questoes', $view);
    }

    public function duplicarQuestoes($idQuestao)
    {
    	$ret = $this->questaoService->duplicarQuestao($idQuestao);
    	if($ret){
			return back()->with('certo', 'Questão duplicada.');
    	}
    	else{
			return back()->with('erro', 'Erro ao tentar duplicar questão.');
    	}

    }

    public function addQuestoes(QuestaoRequest $request)
    {
    	$add = $this->questaoService->addQuestoes($request->all());
    	if($add){
			return redirect('gestao/avaliacao/minhas_questoes')->with('certo', 'Questão criada.');
    	}
    	else{
			return back()->with('erro', 'Erro ao tentar cadastrar questão.');
    	}

    }

    public function publicar($questaoId)
    {
        $retorno = $this->questaoService->publicar($questaoId);

        if($retorno===true){
            return back()->with('certo', 'Publicação da questão alterada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar alterar publicação da questão.');
        }
    }

    public function formQuestoesEditar($id)
    {
        $urlPrevious = url()->previous();
        if(strpos($urlPrevious, 'minhas_questoes?')>0) {
            session(['UrlPreviousQuestoes' => $urlPrevious]);
        }

        $questao = $this->questaoService->getQuestao($id);
        if($questao) {
            $view = [
                'disciplina' => Disciplina::where('titulo', '<>', 'Todos')->orderBy('titulo')->get(),
                'suporte' => FrQuestaoSuporte::orderBy('ordem')->get(),
                'formato' => FrQuestaoFormato::orderBy('ordem')->get(),
                'cicloEtapa' => $this->questaoService->cicloEtapa(),
                'dados' => $questao,
    	    ];
            return view('fr/avaliacao/form_questoes', $view);
        }
        else{
            return redirect('/gestao/avaliacao/minhas_questoes');
        }
    }

    public function updateQuestoes($id, QuestaoRequest $request)
    {
        $urlPrevious = session('UrlPreviousQuestoes');

        if($urlPrevious==''){
            $urlPrevious = '/gestao/avaliacao/minhas_questoes';
        }
    	$add = $this->questaoService->updateQuestoes($id, $request->all());
    	if($add){
			return redirect($urlPrevious)->with('certo', 'Questão editada.');
    	}
    	else{
			return back()->with('erro', 'Erro ao tentar editar questão.');
    	}
    }

    public function excluirQuestoes($id)
    {
    	$add = $this->questaoService->excluirQuestoes($id);
    	if($add){
			return redirect('gestao/avaliacao/minhas_questoes')->with('certo', 'Questão excluída.');
    	}
    	else{
			return back()->with('erro', 'Erro ao tentar excluir questão.');
    	}

    }

    public function getBnccAjaxLista(Request $request)
    {
        $retorno = $this->questaoService->getBnccAjaxLista($request);
        if($retorno!==false){
            return response()->json( $retorno, 200 );
        }
        else
        {
            return response()->json( $retorno, 400 );
        }
    }

    public function getBnccAjax(Request $request)
    {
		$retorno = $this->questaoService->getBnccAjax($request);
        if($retorno!==false){
            return response()->json( $retorno, 200 );
        }
        else
        {
            return response()->json( $retorno, 400 );
        }
    }

    public function getUnidadeTematicaAjaxLista(Request $request)
    {
        $retorno = $this->questaoService->getUnidadeTematicaAjaxLista($request);
        if($retorno!==false){
            return response()->json( $retorno, 200 );
        }
        else
        {
            return response()->json( $retorno, 400 );
        }
    }

    public function getUnidadeTematicaAjax(Request $request)
    {
		$retorno = $this->questaoService->getUnidadeTematicaAjax($request);
        if($retorno!==false){
            return response()->json( $retorno, 200 );
        }
        else
        {
            return response()->json( $retorno, 400 );
        }
    }

    public function getTemaAjaxLista(Request $request)
    {
        $retorno = $this->questaoService->getTemaAjaxLista($request);
        if($retorno!==false){
            return response()->json( $retorno, 200 );
        }
        else
        {
            return response()->json( $retorno, 400 );
        }
    }

    public function getTemaAjax(Request $request)
    {
        $retorno = $this->questaoService->getTemaAjax($request);
        if($retorno!==false){
            return response()->json( $retorno, 200 );
        }
        else
        {
            return response()->json( $retorno, 400 );
        }
    }

    public function verQuestao($id)
    {
		$view = [
			'dados' => $this->questaoService->verQuestao($id),
		];
		return view('fr/avaliacao/ver_questao',$view);
    }

    public function filaFullText(){
        $service = new QuestaoService();
        $service->fila(null);
    }
}
