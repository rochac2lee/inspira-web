<?php

namespace App\Http\Controllers\Fr;

use App\Http\Requests\Fr\AvaliacaoPublicarRequest;
use App\Models\FrAvaliacao;
use App\Models\FrAvaliacaoPlacar;
use App\Http\Requests\Fr\AvaliacaoSalvarQuestaoRequest;
use App\Models\FrAvaliacaoQuestao;
use App\Models\User;
use App\Services\Fr\AvaliacaoRelatorioService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Http\Requests\Fr\AvaliacaoRequest;
use App\Services\Fr\QuestaoService;
use App\Services\Fr\AvaliacaoService;

use App\Models\Disciplina;
use App\Models\FrQuestaoFormato;
use App\Models\FrQuestaoSuporte;

class GestaoAvaliacaoController extends Controller
{
	public function __construct(QuestaoService $questaoService, AvaliacaoService $avaliacaoService, AvaliacaoRelatorioService $avaliacaoRelatorioService)
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
        $this->avaliacaoService = $avaliacaoService;
        $this->avaliacaoRelatorioService = $avaliacaoRelatorioService;
        $this->dificuldade = [
            '0' => 'Fácil',
            '1' => 'Médio',
            '2' => 'Difícil',
        ];

    }

    public function avaliar(Request $request)
    {
        return view('fr/avaliacao/avaliacao');
    }

    public function minhasAvaliacoes(Request $request)
    {
        $turmas = User::with('turmaDeProfessores')->find(auth()->user()->id);

        $view = [
            'dados' => $this->avaliacaoService->minhasAvaliacoes($request),
            'disciplina' => Disciplina::orderBy('titulo')->get(),
            'turmas' => $turmas->turmaDeProfessores,
        ];
        return view('fr/avaliacao/lista_avaliacao',$view);
    }

    private function questoesParaAvaliacao($request)
    {
        $listaQuestoes = $this->questaoService->minhasQuestoes($request, 30);
        $view = [
            'dados' => $listaQuestoes,
            'dificuldade'=>$this->dificuldade,
        ];

        return [
            'questao' => view('fr/avaliacao/lista_questao_avaliacao',$view)->render(),
            'total' => $listaQuestoes->total(),
            'exibindo' => count($listaQuestoes),
        ];
    }

    public function formAvaliacao(Request $request)
    {
        $turmas = User::with('turmaDeProfessores')->find(auth()->user()->id);
        $view = [
            'disciplina' => Disciplina::orderBy('titulo')->get(),
            'cicloEtapa'=> $this->questaoService->cicloEtapa(),
            'formato'=> FrQuestaoFormato::orderBy('ordem')->get(),
            'suporte'=> FrQuestaoSuporte::orderBy('ordem')->get(),
            'fonte'        => $this->questaoService->fonte(),
            'turmas' => $turmas->turmaDeProfessores,
        ];
    	return view('fr/avaliacao/form_avaliacao',$view);
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

        $questao = view('fr/avaliacao/lista_questao_avaliacao',$view)->render();

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
            return redirect('gestao/avaliacao/')->with('certo', 'Avaliação criada.');
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
        $turmas = User::with('turmaDeProfessores')->find(auth()->user()->id);

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
            'turmas' => $turmas->turmaDeProfessores,
        ];
        return view('fr/avaliacao/form_avaliacao',$view);
    }

    public function updateAvaliacao($id, AvaliacaoRequest $request)
    {
        $add = $this->avaliacaoService->updateAvaliacao($id, $request->all());
        if($add){
            return redirect('gestao/avaliacao')->with('certo', 'Avaliação editada.');
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
            return redirect('gestao/avaliacao/')->with('certo', 'Avaliação excluída.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir avaliação.');
        }
    }

    public function relatorioAvaliacaoOnline(Request $request, $idAvaliacao)
    {
        $avaliacao= FrAvaliacao::where('user_id',auth()->user()->id)->where('publicado',1)->find($idAvaliacao);
        if($avaliacao){
            $this->avaliacaoService->confereTotalizados($idAvaliacao, $avaliacao->tempo_maximo);
            $perguntas = unserialize($avaliacao->perguntas);
            $view = [
                'avaliacao' => $avaliacao,
                'dados'=> $this->avaliacaoRelatorioService->relatorio($request->input('tipo'),$idAvaliacao, $perguntas),
            ];
            return view('fr/avaliacao/relatorio/gestao/index',$view);
        }else{
            return back();
        }
    }

    public function relatorioAvaliacaoOcorrenciasOnline(Request $request, $idAvaliacao, $idAluno)
    {
        $avaliacao= FrAvaliacao::where('user_id',auth()->user()->id)->where('publicado',1)->find($idAvaliacao);
        if($avaliacao){
            $timeLine = $this->avaliacaoRelatorioService->getTileLine($idAvaliacao, $idAluno);
            $aluno = null;
            if($timeLine)
            {
                $aluno = User::find($idAluno);
            }
            $view = [
                'avaliacao' => $avaliacao,
                'timeLine'  => $timeLine,
                'aluno' => $aluno
            ];
            return view('fr/avaliacao/relatorio/gestao/relatorio_time_line_ocorrencias',$view);
        }else{
            return back();
        }
    }
    public function getCorrecaoPergunta(Request $request)
    {
        $questao = $this->avaliacaoRelatorioService->getQuestaoAlunoCorrecao($request->all());
        if($questao){
            $dados = view('fr/avaliacao/relatorio/gestao/correcao_questao_aberta',['request'=>$request->except('_token'),'q'=>$questao])->render();

            return response()->json( $dados, 200 );
        }else{
            return response()->json( '', 400 );
        }

    }

    public function salvarCorrecaoPergunta(AvaliacaoSalvarQuestaoRequest $request)
    {
        $questao = $this->avaliacaoService->salvarCorrecaoPergunta($request->all());
        if($questao){
            return back()->with('certo', 'Correção realizada.');
        }else{
            return back()->with('erro', 'Erro ao salvar correção da questão.');
        }

    }

    public function detalhes(Request $request, $idAvaliacao)
    {



        $avaliacao= FrAvaliacao::where('user_id',auth()->user()->id)
                                ->with('disciplina')
                                ->with('alunos')
                                ->with('placar')
                                ->find($idAvaliacao);
        if($avaliacao){
            $view = [
                'avaliacao' => $avaliacao,
                'perguntas' => unserialize($avaliacao->perguntas),
            ];
            //return view('fr.avaliacao.relatorio.impressaoAvaliacaoToPDF',$view);
            //$html = view('fr.avaliacao.relatorio.impressaoAvaliacaoToPDF',$view)->render();
            //$pdf = PDF::loadHTML($html);
            //return $pdf->stream();
            return view('fr/avaliacao/relatorio/detalhes/index',$view);
        }else{
            return back();
        }
    }

    public function impressao(Request $request, $idAvaliacao)
    {
        $avaliacao= FrAvaliacao::where('user_id',auth()->user()->id)
            ->with('disciplina')
            ->with('alunos')
            ->with('placar')
            ->find($idAvaliacao);
        if($avaliacao){
            $view = [
                'avaliacao' => $avaliacao,
                'perguntas' => unserialize($avaliacao->perguntas),
            ];
            return view('fr.avaliacao.relatorio.impressaoAvaliacao',$view);
        }else{
            return back();
        }
    }
}
