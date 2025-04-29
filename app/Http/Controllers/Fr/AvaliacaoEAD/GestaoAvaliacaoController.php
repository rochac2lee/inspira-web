<?php

namespace App\Http\Controllers\Fr\AvaliacaoEAD;

use App\Http\Requests\Fr\Ead\QuestaoRequest;
use App\Http\Requests\Fr\Indica\AvaliacaoPublicarRequest;
use App\Models\InstituicaoTipo;
use App\Services\Fr\AvaliacaoEAD\AvaliacaoRelatorioService;
use App\Services\Fr\AvaliacaoEAD\AvaliacaoService;
use App\Services\Fr\AvaliacaoEAD\QuestaoService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Http\Requests\Fr\Ead\AvaliacaoRequest;

use App\Models\Disciplina;
use App\Models\FrQuestaoFormato;
use App\Models\FrQuestaoSuporte;

class GestaoAvaliacaoController extends Controller
{
	public function __construct(QuestaoService $questaoService, AvaliacaoService $avaliacaoService, AvaliacaoRelatorioService $avaliacaoRelatorioService)
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
        $this->avaliacaoService = $avaliacaoService;
        $this->avaliacaoRelatorioService = $avaliacaoRelatorioService;
        $this->dificuldade = [
            '0' => 'Fácil',
            '1' => 'Médio',
            '2' => 'Difícil',
        ];

    }


    public function minhasAvaliacoes(Request $request)
    {
        $view = [
            'dados' => $this->avaliacaoService->minhasAvaliacoes($request),
            'disciplina' => Disciplina::orderBy('titulo')->get(),
            'cicloEtapa'=> $this->questaoService->cicloEtapa(),
        ];
        return view('fr/avaliacao_ead/avaliacao/lista_avaliacao',$view);
    }

    private function questoesParaAvaliacao($request)
    {
        $listaQuestoes = $this->questaoService->minhasQuestoes($request, 30);
        $view = [
            'dados' => $listaQuestoes,
            'dificuldade'=>$this->dificuldade,
        ];

        return [
            'questao' => view('fr/avaliacao_ead/avaliacao/lista_questao_avaliacao',$view)->render(),
            'total' => $listaQuestoes->total(),
            'exibindo' => count($listaQuestoes),
        ];
    }

    public function formAvaliacao(Request $request)
    {

        $view = [
            'disciplina' => Disciplina::orderBy('titulo')->get(),
            'cicloEtapa'=> $this->questaoService->cicloEtapa(),
            'formato'=> FrQuestaoFormato::orderBy('ordem')->get(),
            'suporte'=> FrQuestaoSuporte::orderBy('ordem')->get(),
            'fonte'        => $this->questaoService->fonte(),
            'tipoInstituicao' => InstituicaoTipo::orderBy('titulo')->get(),
        ];
    	return view('fr/avaliacao_ead/avaliacao/form_avaliacao',$view);
    }

    public function getQuestaoAjax(Request $request)
    {
        $listaQuestoes = $this->questoesParaAvaliacao($request);

        if($listaQuestoes!==false){
            return response()->json( $listaQuestoes, 200 );
        }
        else
        {
            return response()->json( false, 400 );
        }
    }

    public function getQuestaoSelecionadasAjax(Request $request)
    {
        $dados = $request->all();

        $listaQuestoes = $this->questaoService->questoesSelecionadas($dados['selecionado']);
        $view = [
            'dados' => $listaQuestoes,
            'dificuldade'=>$this->dificuldade,
        ];

        $questao = view('fr/avaliacao_ead/avaliacao/lista_questao_avaliacao',$view)->render();

        if($questao!==false){
            return response()->json( $questao, 200 );
        }
        else
        {
            return response()->json( false, 400 );
        }
    }

    public function addAvaliacao(AvaliacaoRequest $request)
    {
        $add = $this->avaliacaoService->addAvaliacao($request->all());
        if($add){
            return redirect('avaliacao_ead/gestao/avaliacao/')->with('certo', 'Avaliação criada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cadastrar avaliação.');
        }
    }

    public function publicar(Request $request)
    {
        $retorno = $this->avaliacaoService->publicar($request->all());

        if($retorno===true){
            return back()->with('certo', 'Publicação da avaliação alterada.');
        }
        else{
            $msg = 'Erro ao tentar alterar publicação da avaliação.';
            if($retorno !== false){
                $msg = $retorno;
            }
            return back()->with('erro', $msg);
        }
    }

    public function formAvaliacaoEditar($id, Request $request)
    {
        $avaliacao = $this->avaliacaoService->getAvaliacao($id);
        if(!$avaliacao || $avaliacao->publicado ==1)
        {
           return back()->with('erro', 'Erro ao tentar encontrar avaliação.');
        }

        $request->request->add(['selecionados' => implode(",", $avaliacao->questao)]);

        $view = [
            'disciplina' => Disciplina::orderBy('titulo')->get(),

            'cicloEtapa'=> $this->questaoService->cicloEtapa(),
            'formato'=> FrQuestaoFormato::orderBy('ordem')->get(),
            'suporte'=> FrQuestaoSuporte::orderBy('ordem')->get(),
            'dados'=> $avaliacao,
            'fonte'        => $this->questaoService->fonte(),
            'tipoInstituicao' => InstituicaoTipo::orderBy('titulo')->get(),
        ];
        return view('fr/avaliacao_ead/avaliacao/form_avaliacao',$view);
    }

    public function updateAvaliacao($id, AvaliacaoRequest $request)
    {
        $add = $this->avaliacaoService->updateAvaliacao($id, $request->all());
        if($add){
            return redirect('avaliacao_ead/gestao/avaliacao')->with('certo', 'Avaliação editada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar questão.');
        }
    }

    public function duplicar($idAvaliacao)
    {
        $retorno = $this->avaliacaoService->duplicar($idAvaliacao);
        if($retorno===true){
            return back()->with('certo', 'Avaliação duplicada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar duplicar avaliação.');
        }
    }

    public function excluirAvaliacao($idAvaliacao)
    {
        $add = $this->avaliacaoService->excluirAvaliacao($idAvaliacao);
        if($add){
            return redirect('avaliacao_ead/gestao/avaliacao/')->with('certo', 'Avaliação excluída.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir avaliação.');
        }
    }

    public function cancelarAvaliacao($idAvaliacao)
    {
        $add = $this->avaliacaoService->cancelarAvaliacao($idAvaliacao);
        if($add){
            return redirect('avaliacao_ead/gestao/avaliacao/')->with('certo', 'Avaliação cancelada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cancelar avaliação.');
        }
    }

    public function revisao($idAvaliacao)
    {
        $view = [
            'dados' => $this->avaliacaoService->listaQuestaoRevisao($idAvaliacao),
            'dificuldade' => $this->dificuldade,
            'avaliacao_id' => $idAvaliacao,
        ];
        return view('fr/avaliacao_ead/avaliacao/lista_revisao',$view);
    }

    public function revisaoEditarQuestao($idAvaliacao,$idQuestao){
        $questao = $this->avaliacaoService->getQuestaoRevisao($idAvaliacao,$idQuestao);
        if($questao) {
            $view = [
                'disciplina' => Disciplina::where('titulo', '<>', 'Todos')->orderBy('titulo')->get(),
                'suporte' => FrQuestaoSuporte::orderBy('ordem')->get(),
                'formato' => FrQuestaoFormato::orderBy('ordem')->get(),
                'cicloEtapa' => $this->questaoService->cicloEtapa(),
                'dados' => $questao,
                'revisar' => true,
                'avaliacao_id' => $idAvaliacao,
            ];
            return view('fr/avaliacao_ead/questao/form_questoes', $view);
        }
        else{
            return redirect('avaliacao_ead/gestao/avaliacao/revisao/'.$idAvaliacao);
        }
    }

    public function revisaoUpdateQuestoes($idAvaliacao, $idQuestao, QuestaoRequest $request)
    {
        $add = $this->avaliacaoService->updateQuestoesRevisao($idAvaliacao, $idQuestao, $request->all());
        if($add){
            return redirect('avaliacao_ead/gestao/avaliacao/revisao/'.$idAvaliacao)->with('certo', 'Questão revisada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar questão.');
        }
    }
}
