<?php

namespace App\Http\Controllers\Fr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;

use App\Http\Requests\Fr\ImportacaoAlunoRequest;


use App\Services\Fr\EscolaService;
use App\Services\Fr\InstituicaoService;
use App\Services\Fr\AlunoService;

use App\Models\Escola;

class AlunoController extends Controller
{
    public function __construct(EscolaService $escolaService, InstituicaoService $instituicaoService, AlunoService $alunoService)
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if(Auth::user()->permissao != 'Z' && Auth::user()->permissao != 'I')
            {
                return back();
            }
            return $next($request);
        });

        $this->escolaService        = $escolaService;
        $this->instituicaoService   = $instituicaoService;
        $this->alunoService     = $alunoService;
    }

    public function index($idEscola, Request $request)
    {
    	$view = [
                'dados' => $this->alunoService->getLista($idEscola,$request),
                'escola'=> Escola::find($idEscola),
                'idEscola'=> $idEscola,
            ];
        return view('fr/gestao/escola/aluno/lista',$view);
    }

    public function importacao(ImportacaoAlunoRequest $request)
    {
    	$retorno = $this->alunoService->importar($request);
    	$msgErro = '';
    	if($retorno['qtdErro']>0)
    	{
    		$msgErro = $retorno['qtdErro'].' registros não inseridos.';
    	}
    	return back()
    			->with('certo', 'Arquivo processado. <br><br>'.$retorno['qtdCerto'].' registros inseridos.' )
    			->with('erro', $msgErro );
    }
}
