<?php

namespace App\Http\Controllers\Fr\Indica;

use App\Models\Indica\ImportaLogQuestao;
use App\Models\Indica\ImportaQuestao;
use App\Services\Fr\Indica\ImportaQuestaoService;
use App\Services\Fr\Indica\QuestaoService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Http\Requests\Fr\Indica\QuestaoRequest;

use App\Models\Disciplina;
use App\Models\FrQuestaoFormato;
use App\Models\FrQuestaoSuporte;
use Illuminate\Support\Facades\Storage;

class GestaoQuestaoController extends Controller
{
	public function __construct(QuestaoService $questaoService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if(Auth::user()->permissao != 'Z' )
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
    	return view('fr/indica/questao/lista_questoes', $view);
    }



    public function formQuestoes()
    {
    	$view = [
            'disciplina' => Disciplina::where('titulo','<>','Todos')->orderBy('titulo')->get(),
            'suporte' => FrQuestaoSuporte::orderBy('ordem')->get(),
    		'formato' => FrQuestaoFormato::orderBy('ordem')->get(),
    		'cicloEtapa'=> $this->questaoService->cicloEtapa(),
    	];
    	return view('fr/indica/questao/form_questoes', $view);
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
			return redirect('indica/gestao/questao')->with('certo', 'Questão criada.');
    	}
    	else{
			return back()->with('erro', 'Erro ao tentar cadastrar questão.');
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
            return view('fr/indica/questao/form_questoes', $view);
        }
        else{
            return redirect('indica/gestao/questao');
        }
    }

    public function updateQuestoes($id, QuestaoRequest $request)
    {
        $urlPrevious = session('UrlPreviousQuestoes');

        if($urlPrevious==''){
            $urlPrevious = 'indica/gestao/questao';
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
			return redirect('indica/gestao/questao')->with('certo', 'Questão excluída.');
    	}
    	else{
			return back()->with('erro', 'Erro ao tentar excluir questão.');
    	}

    }

    public function verQuestao($id)
    {
		$view = [
			'dados' => $this->questaoService->verQuestao($id),
		];
		return view('fr/indica/questao/ver_questao',$view);
    }

    public function formImportaQuestao()
    {
        $importaQuestao = new ImportaQuestaoService();
        $view =[
            'dados' => $importaQuestao->listaImportados(),
        ] ;
        return view('fr/indica/questao/form_importa_questoes',$view);
    }

    public function importaQuestao(Request $request)
    {
        $importaQuestao = new ImportaQuestaoService();
        $importado = $importaQuestao->importar($request->all());
        if($importado){
            return back()->with('certo', 'Importação iniciada.');
        }
        else{
            return back()->with('erro', 'Erro no processo de importação.');
        }
    }

    public function download($id){
        $arquivo = ImportaQuestao::find($id);
        ob_end_clean();
        return Storage::download($arquivo->caminho, $arquivo->nome_arquivo);
    }

    public function detalhes($id){

        $view =[
            'arquivo' => ImportaQuestao::find($id),
            'dados' => ImportaLogQuestao::where('importacao_id',$id)->where('erro',1)->paginate(20),
        ];
        return view('fr.indica.questao.detalhes_importa_questao',$view);
    }
}
