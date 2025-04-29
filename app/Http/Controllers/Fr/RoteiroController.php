<?php

namespace App\Http\Controllers\Fr;

use App\Models\Conteudo;
use App\Services\Fr\RoteiroConteudoService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Fr\RoteiroService;
use Illuminate\Support\Facades\Storage;

class RoteiroController extends Controller
{
    public function __construct(RoteiroService $roteiroService, RoteiroConteudoService $roteiroConteudoService)
    {
        $this->middleware('auth');
        $this->roteiroService = $roteiroService;
        $this->roteiroConteudoService = $roteiroConteudoService;
	}

	public function iniciarRoteiro($roteiroId, $trilhaId){
        $roteiro = $this->roteiroService->get($roteiroId, true, $trilhaId );
        if(!$roteiro)
        {
            return back()->with('erro', 'Erro ao tentar encontrar o roteiro.');
        }

        $view = [
            'roteiro' => $roteiro,
            'executar' => true,
            'trilhaId' => $trilhaId,
        ];
        return view('fr.roteiro.exibe.index',$view);
    }

    public function realizarRoteiro($roteiroId, $trilhaId){
        $roteiro = $this->roteiroService->get($roteiroId, true, $trilhaId);
        if(!$roteiro)
        {
            return back()->with('erro', 'Erro ao tentar encontrar roteiro.');
        }

        $view = [
            'roteiro' => $roteiro,
            'executar'=> true,
            'trilhaId'=> $trilhaId,
        ];

        return view('fr.roteiro.exibe.realizar',$view);
    }

    public function getConteudoAjax(Request $request){
        $conteudo = $this->roteiroConteudoService->getConteudo($request->input('curso_id'), $request->input('aula_id'), $request->input('conteudo_id'), true, $request->input('trilha_id'));
        $status = 500;
        if($conteudo)
        {
            $status = 200;
        }
        return response()->json( $conteudo, $status );
    }

    public function salvaEntregavel(Request $request){
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
        $entregavel = $this->roteiroConteudoService->salvaDiscursiva($request->all());
        $status = 500;
        if($entregavel)
        {
            $status = 200;
        }
        return response()->json( '', $status );
    }

    public function listaEntregavel(Request $request){
        $entregavel = $this->roteiroConteudoService->listaEntregavel($request->all());
        $status = 500;
        if($entregavel)
        {
            $status = 200;
        }
        return response()->json( $entregavel, $status );
    }

    public function downloadEntregavel(Request $request){
        $caminho = $this->roteiroConteudoService->caminhoDownloadEntregavel($request->all());
        ob_end_clean();
        return Storage::download($caminho);
    }

    public function getDiscursiva(Request $request){
        $entregavel = $this->roteiroConteudoService->getDiscursiva($request->all());
        $status = 200;
        return response()->json( $entregavel, $status );
    }

    public function download($id,$nome){
        $nome = str_replace('_','.',$nome);
        $cont = Conteudo::where('conteudo',$nome)->find($id);
        if($cont) {
            ob_end_clean();
            $link = config('app.frStorage').'uploads/conteudos/'.$cont->conteudo;
            return Storage::download($link);
        }
        else{
            return back()->with('erro', 'Erro ao tentar realizar download do arquivo.');
        }
    }
}
