<?php

namespace App\Http\Controllers\Fr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Fr\EdInfantilService;

class EdInfantilController extends Controller
{
	public function __construct(EdInfantilService $edInfantilService)
    {
        $this->middleware('auth');
        $this->edInfantilService = $edInfantilService;
    }

    public function index()
    {
    	return view('fr/ed_infantil/index');
    }

    public function colecao($colecaoId, Request $request)
    {
    	$permissao = $this->edInfantilService->permissaoProfessorColecao($colecaoId);
    	if($permissao != 0)
    	{
    		return back();
    	}
    	$view = [
    		'classe' 	=> $this->edInfantilService->classeColecao($colecaoId),
    		'dados' 	=> $this->edInfantilService->getConteudo($colecaoId,$request),
    		'colecaoId'	=> $colecaoId,
    	];
    	return view('fr/ed_infantil/material',$view);
    }

    public function colecaoProfessor()
    {
    	if(auth()->user()->permissao == 'A')
    	{
    		return back();
    	}
    	$view = [
    		'classe' 	=> 'professor',
    	];
    	return view('fr/ed_infantil/colecao_professor', $view);
    }

    public function materialColecaoProfessor($colecaoId, Request $request)
    {
		if(auth()->user()->permissao == 'A')
    	{
    		return back();
    	}
    	$view = [
    		'classe' 	=> 'professor',
    		'dados' 	=> $this->edInfantilService->getConteudo($colecaoId,$request),
    		'colecaoId'	=> $colecaoId,
    	];
    	return view('fr/ed_infantil/material',$view);
    }

    public function getConteudoAjax($colecaoId, Request $request)
    {

    	$dados = $this->edInfantilService->getConteudoAjax($colecaoId,$request);

    	return response()->json( $dados, 200 );
    }

}
