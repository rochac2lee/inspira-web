<?php

namespace App\Http\Controllers\Fr;

use App\Services\Fr\UsuarioService;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;

use App\Http\Requests\Fr\ImportacaoProfessorRequest;


use App\Services\Fr\EscolaService;
use App\Services\Fr\InstituicaoService;
use App\Services\Fr\ProfessorService;

use App\Models\Escola;

class ProfessorController extends Controller
{
    public function __construct(UsuarioService $usuarioService, EscolaService $escolaService, InstituicaoService $instituicaoService, ProfessorService $professorService)
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
        $this->professorService     = $professorService;
        $this->usuarioService     = $usuarioService;
    }

    public function index($idEscola, Request $request)
    {
    	$view = [
                'dados' => $this->professorService->getLista($idEscola,$request),
                'escola'=> Escola::find($idEscola),
            ];
        return view('fr/gestao/escola/professor/lista',$view);
    }

    public function importacao(ImportacaoProfessorRequest $request)
    {
    	$retorno = $this->professorService->importar($request);
    	$msgErro = '';
    	if($retorno['qtdErro']>0)
    	{
    		$msgErro = $retorno['qtdErro'].' registros não inseridos.';
    	}
    	return back()
    			->with('certo', 'Arquivo processado. <br><br>'.$retorno['qtdCerto'].' registros inseridos.' )
    			->with('erro', $msgErro );
    }

    public function logar(Request $request, $escolaId, $professorId)
    {
        if(Auth::user()->permissao != 'Z' )
        {
            return back();
        }
        $user = User::find($professorId);
        auth()->guard()->logout();
        $request->session()->invalidate();
        auth()->login($user);
        $this->usuarioService->gravaSessaoUsuario();
        return redirect('/');

    }
}
