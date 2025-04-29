<?php

namespace App\Http\Controllers\Fr\Indica;

use App\Http\Requests\Fr\Indica\AvaliacaoPublicarRequest;
use App\Models\Indica\Avaliacao;
use App\Models\Indica\AvaliacaoTentativas;
use App\Models\InstituicaoTipo;
use App\Models\User;
use App\Services\Fr\Agenda\FamiliaService;
use App\Services\Fr\Indica\AvaliacaoRelatorioService;
use App\Services\Fr\Indica\AvaliacaoService;
use App\Services\Fr\Indica\QuestaoService;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Http\Requests\Fr\Indica\AvaliacaoRequest;

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
            'dados' => $this->avaliacaoService->minhasAvaliacoes($request,['filtroTipo'=>true]),
            'disciplina' => Disciplina::orderBy('titulo')->get(),
            'cicloEtapa'=> $this->questaoService->cicloEtapa(),
        ];
        return view('fr/indica/avaliacao/lista_avaliacao',$view);
    }

    private function questoesParaAvaliacao($request)
    {
        $listaQuestoes = $this->questaoService->minhasQuestoes($request, 30);
        $view = [
            'dados' => $listaQuestoes,
            'dificuldade'=>$this->dificuldade,
        ];

        return [
            'questao' => view('fr/indica/avaliacao/lista_questao_avaliacao',$view)->render(),
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
    	return view('fr/indica/avaliacao/form_avaliacao',$view);
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

        $questao = view('fr/indica/avaliacao/lista_questao_avaliacao',$view)->render();

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
            return redirect('indica/gestao/avaliacao/')->with('certo', 'Avaliação criada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cadastrar avaliação.');
        }
    }

    public function publicar(AvaliacaoPublicarRequest $request)
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
        return view('fr/indica/avaliacao/form_avaliacao',$view);
    }

    public function updateAvaliacao($id, AvaliacaoRequest $request)
    {
        $add = $this->avaliacaoService->updateAvaliacao($id, $request->all());
        if($add){
            return redirect('indica/gestao/avaliacao')->with('certo', 'Avaliação editada.');
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
            return redirect('indica/gestao/avaliacao/')->with('certo', 'Avaliação excluída.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir avaliação.');
        }
    }

    public function cancelarAvaliacao($idAvaliacao)
    {
        $add = $this->avaliacaoService->cancelarAvaliacao($idAvaliacao);
        if($add){
            return redirect('indica/gestao/avaliacao/')->with('certo', 'Avaliação cancelada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar cancelar avaliação.');
        }
    }

    public function permissoes(Request $request)
    {
        $dados = $this->avaliacaoService->permissaoAvaliacao($request->input('id'));
        return response()->json( $dados, 200 );
    }

    public function relatorio($idAvaliacao)
    {
        $retorno = $this->avaliacaoService->relatorio($idAvaliacao, true);
      //  dd($retorno);
        if($retorno!==false){
            $view = [
                'relatorio' => $retorno['relatorio'],
                'avaliacao' => $retorno['avaliacao'],
                'ordemPerguntas' => $retorno['ordemPerguntas'],
                'questoesAlunos' => $retorno['questoesAlunos'],
                'disciplina' => $retorno['disciplina'],
                'ocorrencias' => AvaliacaoTentativas::where('indica_avaliacao_id', $idAvaliacao)
                    ->where('iniciou',0)
                    ->whereHas('placar',function($q){
                        $q->where('indica_avaliacao_placar.user_id', DB::raw('indica_avaliacao_tentativas.user_id'));
                    })
                    ->distinct('user_id')
                    ->count(),
            ];
            return view('fr.indica.avaliacao.relatorio.relatorio_adm',$view);
        }
        else{
            return back()->with('erro', 'Erro ao tentar gerar relatório.');
        }
    }

    public function relatorio_ocorrencias($idAvaliacao)
    {
        $retorno = Avaliacao::selectRaw('id, titulo')->find($idAvaliacao);
        //dd($retorno);
        if($retorno){
            $view = [
                'avaliacao' => $retorno,
                'dados' =>  $this->avaliacaoService->relatorio_ocorrencia($idAvaliacao),
            ];
            return view('fr.indica.avaliacao.relatorio.relatorio_adm_ocorrencias',$view);
        }
        else{
            return back()->with('erro', 'Erro ao tentar gerar relatório.');
        }
    }

    public function relatorio_ocorrencias_detalhes($idAvaliacao, $idAluno)
    {
        $retorno = Avaliacao::selectRaw('id, titulo')->find($idAvaliacao);
        //dd($retorno);
        $dados = $this->avaliacaoService->relatorio_ocorrencia_detalhes($idAvaliacao,$idAluno);
        if($dados){
            $view = [
                'avaliacao' => $retorno,
                'dados' =>  $dados,
            ];
            return view('fr.indica.avaliacao.relatorio.relatorio_adm_ocorrencias_detalhes',$view);
        }
        else{
            return back()->with('erro', 'Estudante nao encontrado na avaliação.');
        }
    }

    public function download($idAvaliacao)
    {
        $fileName = \Str::random(20).'.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $retorno = $this->avaliacaoService->relatorio($idAvaliacao);
        //dd($retorno);
        if($retorno!==false){
            $dwn = $this->avaliacaoService->relatorioAdmDownload($retorno['relatorio'],$retorno['questoesAlunos'],$retorno['ordemPerguntas'],$retorno['disciplina'],$retorno['avaliacao']);
            return response()->stream($dwn, 200, $headers);
        }
        else{
            return back()->with('erro', 'Erro ao tentar gerar relatório.');
        }
    }

}
