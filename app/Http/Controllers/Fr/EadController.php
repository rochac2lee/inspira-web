<?php

namespace App\Http\Controllers\Fr;

use App\Models\Altitude\TrilhasMatriculasUsuario;
use App\Models\Disciplina;
use App\Models\Ead\AvaliacaoPlacarHistorico;
use App\Services\Fr\AvaliacaoEAD\AvaliacaoService;
use App\Services\Fr\RoteiroConteudoService;
use App\Services\Fr\RoteiroService;
use App\Services\Fr\TrilhasService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EadController extends Controller
{
	public function __construct(TrilhasService $trilhasService, RoteiroService $roteiroService, RoteiroConteudoService $roteiroConteudoService, AvaliacaoService $avaliacaoService)
    {
        $this->middleware('auth');
        $this->trilhasService = $trilhasService;
        $this->roteiroService = $roteiroService;
        $this->roteiroConteudoService = $roteiroConteudoService;
        $this->avaliacaoService = $avaliacaoService;
    }

    public function index(Request $request)
    {
        $busca = $request->all();
        $busca['ead'] = true;
        $busca['realizar'] = true;
        $busca['index'] = true;
        $trilhas = $this->trilhasService->getLista(20,$busca);
        $estatistica =  $this->trilhasService->getEstatistica( $trilhas->pluck('id')->toArray());
        $view = [
            'ead' => true,
            'trilhas' => $trilhas,
            'estatistica' =>$estatistica,
            'cicloEtapa' => $this->trilhasService->cicloEtapa(),
            'disciplina' => Disciplina::orderBy('titulo')->get(),
        ];
       return view('fr/trilhas/index_aluno', $view);
    }

    public function detalhes($id)
    {
        $dados = [
            'ead'       => true,
            'realizar'  => true,
            'id'        => $id,
            ];
        $retorno = $this->trilhasService->detalhes($dados);
        if($retorno){
            $view = [
                'ead' => true,
                'dados' => $retorno,
                'qtdMatriculasEADSemestre' => $this->trilhasService->qtdMatriculasEADSemestre(),
            ];
            return view('fr/trilhas/detalhes', $view);
        }
        else{
            return back()->with('erro', 'Erro ao tentar mostrar detalhes.');
        }
    }

    public function matricular($id)
    {
        $dados = [
            'ead'       => true,
            'realizar'  => true,
            'id'        => $id,
        ];
        $retorno = $this->trilhasService->matricular($dados);

        if($retorno===true){

            return redirect('ead/matriculado/'.$id.'/roteiro');
        }
        else{
            return back()->with('erro', 'Erro ao tentar realizar matricula.');
        }
    }

    public function listaRoteiro($id)
    {
        $dados = [
            'ead'       => true,
            'realizar'  => true,
            'id'        => $id,
        ];
        $retorno = $this->trilhasService->listaRoteiro($dados);
        if($retorno!==false){
            $realizada = false;
            if(isset($retorno->avaliacao->id) && $retorno->avaliacao->id){
                $avaliacao = AvaliacaoPlacarHistorico::where('ead_avaliacao_id', $retorno->avaliacao->id)->where('user_id',auth()->user()->id)->first();
                    //$this->avaliacaoService->getAvaliacaoAlunos($retorno->avaliacao->id, $id,true);
                if($avaliacao){
                    $realizada = true;
                }
            }
            $estatistica =  $this->trilhasService->getEstatisticaRoteiro( $retorno->cursos->pluck('id')->toArray());
            $matricula = TrilhasMatriculasUsuario::where('user_id',auth()->user()->id)->where('trilha_id', $id)->first();
            $view = [
                'trilhaId'      => $id,
                'ead'          => true,
                'dados'        => $retorno,
                'avaliacaoRealizada' => $realizada,
                'estatistica'  => $estatistica,
                'estatisticaTrilha'=> $this->trilhasService->getEstatistica( [$id]),
                'matricula' => $matricula,
            ];
            return view('fr/roteiro/index_aluno', $view);
        }
        else{
            return back()->with('erro', 'Erro ao tentar listar roteiros.');
        }
    }

    public function iniciarRoteiro($roteiroId, $trilhaId){
        $dados = [
            'ead'       => true,
            'realizar'  => true,
        ];
        $trilha = $this->trilhasService->get($trilhaId,$dados);
        if(!$trilha){
            return back()->with('erro', 'Erro ao tentar encontrar trilha.');
        }
        $roteiro = $this->roteiroService->get($roteiroId, true, $trilhaId );
        if(!$roteiro)
        {
            return back()->with('erro', 'Erro ao tentar encontrar o roteiro.');
        }

        $view = [
            'ead' => true,
            'roteiro' => $roteiro,
            'executar' => true,
            'trilhaId' => $trilhaId,
        ];
        return view('fr.roteiro.exibe.index',$view);
    }

    public function realizarRoteiro($roteiroId, $trilhaId){
        $dados = [
            'ead'       => true,
            'realizar'  => true,
        ];
        $trilha = $this->trilhasService->get($trilhaId,$dados);
        if(!$trilha){
            return back()->with('erro', 'Erro ao tentar encontrar trilha.');
        }

        $roteiro = $this->roteiroService->get($roteiroId, true, $trilhaId);
        if(!$roteiro)
        {
            return back()->with('erro', 'Erro ao tentar encontrar roteiro.');
        }

        $view = [
            'roteiro' => $roteiro,
            'ead'=> true,
            'executar'=> true,
            'trilhaId'=> $trilhaId,
        ];

        return view('fr.roteiro.exibe.realizar',$view);
    }

    public function getConteudoAjax(Request $request){
        $dados = [
            'ead'       => true,
            'realizar'  => true,
        ];
        $trilha = $this->trilhasService->get($request->input('trilha_id'),$dados);
        if(!$trilha){
            return response()->json( 'Erro ao tentar encontrar trilha', 500 );
        }

        $conteudo = $this->roteiroConteudoService->getConteudo($request->input('curso_id'), $request->input('aula_id'), $request->input('conteudo_id'), true, $request->input('trilha_id'));
        $status = 500;
        if($conteudo)
        {
            $status = 200;
        }
        return response()->json( $conteudo, $status );
    }

    public function salvaEntregavel(Request $request){
        $dados = [
            'ead'       => true,
            'realizar'  => true,
        ];
        $trilha = $this->trilhasService->get($request->input('trilha_id'),$dados);
        if(!$trilha){
            return response()->json( 'Erro ao tentar encontrar trilha', 500 );
        }

        $entregavel = $this->roteiroConteudoService->salvaEntregavel($request->all());
        $status = 500;
        $retorno = '';
        if($entregavel)
        {
            $retorno = $this->roteiroConteudoService->listaEntregavel($request->all());
            $status = 200;
        }
        return response()->json( $retorno, $status );
    }

    public function salvaDiscursiva(Request $request){
        $dados = [
            'ead'       => true,
            'realizar'  => true,
        ];
        $trilha = $this->trilhasService->get($request->input('trilha_id'),$dados);
        if(!$trilha){
            return response()->json( 'Erro ao tentar encontrar trilha', 500 );
        }

        $entregavel = $this->roteiroConteudoService->salvaDiscursiva($request->all());
        $status = 500;
        if($entregavel)
        {
            $status = 200;
        }
        return response()->json( '', $status );
    }

    public function listaEntregavel(Request $request){
        $dados = [
            'ead'       => true,
            'realizar'  => true,
        ];
        $trilha = $this->trilhasService->get($request->input('trilha_id'),$dados);
        if(!$trilha){
            return response()->json( 'Erro ao tentar encontrar trilha', 500 );
        }
        $entregavel = $this->roteiroConteudoService->listaEntregavel($request->all());
        $status = 500;
        if($entregavel)
        {
            $status = 200;
        }
        return response()->json( $entregavel, $status );
    }

    public function getDiscursiva(Request $request){
        $dados = [
            'ead'       => true,
            'realizar'  => true,
        ];
        $trilha = $this->trilhasService->get($request->input('trilha_id'),$dados);
        if(!$trilha){
            return response()->json( 'Erro ao tentar encontrar trilha', 500 );
        }
        $entregavel = $this->roteiroConteudoService->getDiscursiva($request->all());
        return response()->json( $entregavel );
    }

    public function relatorio($id){
        $perm = auth()->user()->permissao;
        if($perm != 'Z' && $perm != 'I' && $perm != 'C'){
            return back();
        }

        $dados = $this->trilhasService->get($id, ['ead' => true, 'realizar'=>true, 'index'=>true]);
        $relatorio = $this->trilhasService->getRoteiroConteudoRelatorio($dados->id, true);
        $view = [
            'ead' => true,
            'dados' => $dados,
            'relatorio' => $relatorio,
            'cursados' => $this->trilhasService->getRealizadosTrilhasRoteiros($dados->id),
            'percCursados' => $this->trilhasService->getPercAlunosTrilha($id, $relatorio->matriculas->pluck('id')),
        ];
        return view('fr/trilhas/relatorio', $view);
    }

    public function certificado($id){
        $certificado = $this->avaliacaoService->certificado($id);
        if(!$certificado){
            return back()->with('erro', 'Erro ao tentar gerar certificado.');
        }
        return view('fr.ead_certificado', ['certificado'=>$certificado]);
    }

}
