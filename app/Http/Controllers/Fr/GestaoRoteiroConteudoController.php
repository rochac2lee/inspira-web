<?php

namespace App\Http\Controllers\Fr;

use App\Http\Requests\Fr\RoteiroConteudoBibliotecaRequest;
use App\Http\Requests\Fr\RoteiroConteudoRequest;
use App\Http\Controllers\Controller;

use App\Models\Conteudo;
use App\Services\Fr\RoteiroConteudoService;
use App\Services\Fr\RoteiroService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GestaoRoteiroConteudoController extends Controller
{
    public function __construct(RoteiroService $roteiroService, RoteiroConteudoService $roteiroConteudoService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if(Auth::user()->permissao == 'A' )
            {
                return back();
            }
            return $next($request);
        });
        $this->roteiroService = $roteiroService;
        $this->roteiroConteudoService = $roteiroConteudoService;
	}

    public function add(RoteiroConteudoRequest $request)
    {
        if($request->input('op') == 'inserir'){
            $retorno = $this->roteiroConteudoService->inserir($request->all());
            $certo = 'Conteúdo cadastrado.';
            $erro = 'Erro ao tentar cadastrar conteúdo.';
        }elseif($request->input('op') == 'editar'){
            $retorno = $this->roteiroConteudoService->editar($request->all());
            $certo = 'Conteúdo editado.';
            $erro = 'Erro ao tentar editar conteúdo.';
        }
        else{
            return back();
        }

        if($retorno===true){
            return redirect('/gestao/roteiros/'.$request->input('curso_id').'/editar_conteudo?tema='.$request->input('aula_id'))->with('certo', $certo);
        }
        else{
            return back()->with('erro', $erro);
        }
    }

    public function addBiblioteca(RoteiroConteudoBibliotecaRequest $request)
    {
        $retorno = $this->roteiroConteudoService->inserirBiblioteca($request->all());
        if($retorno===true){
            return redirect('/gestao/roteiros/'.$request->input('curso_id').'/editar_conteudo?tema='.$request->input('aula_id'))->with('certo', 'Conteúdo cadastrado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar editar conteúdo.');
        }
    }

    public function delete($cursoId, $temaId, $conteudoId )
    {
        $retorno = $this->roteiroConteudoService->delete($cursoId, $temaId, $conteudoId);
        if($retorno){
            return redirect('/gestao/roteiros/'.$cursoId.'/editar_conteudo?tema='.$temaId)->with('certo', 'Conteúdo excluído.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir conteúdo.');
        }
    }

    public function duplicar($cursoId, $temaId, $conteudoId )
    {
        $retorno = $this->roteiroConteudoService->duplicar($cursoId, $temaId, $conteudoId, $temaId);

        if($retorno===true){
            return redirect('/gestao/roteiros/'.$cursoId.'/editar_conteudo?tema='.$temaId)->with('certo', 'Conteúdo duplicado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar duplicar conteúdo.');
        }
    }

    public function ordemConteudo(Request $request){
        $ordem = $this->roteiroConteudoService->ordemConteudo($request->all());

        $status = 200;
        if(!$ordem)
        {
            $status = 500;
        }
        return response()->json( $ordem, $status );
    }

    public function getConteudoAjax(Request $request){
        $conteudo = $this->roteiroConteudoService->getConteudo($request->input('curso_id'), $request->input('aula_id'),  $request->input('conteudo_id'),null, null);
        $status = 200;
        if(!$conteudo)
        {
            $status = 500;
        }
        return response()->json( $conteudo, $status );
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
