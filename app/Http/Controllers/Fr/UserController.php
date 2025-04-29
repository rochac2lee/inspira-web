<?php

namespace App\Http\Controllers\Fr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Fr\UsuarioService;

use Auth;

class UserController extends Controller
{
	public function __construct(UsuarioService $usuarioService)
    {
        $this->middleware('auth');

        $this->usuarioService = $usuarioService;
    }

    public function listaPermissoes(Request $request)
    {
        $permissoes = $this->usuarioService->listaPermissoes();
        if(count($permissoes)>0 || auth()->user()->permissao == 'R' || auth()->user()->permissao == 'I') {
            $view = [
                'dados' => $permissoes,

            ];
            return view('fr/usuario/multiPermissao/lista_permissao', $view);
        }
        else{
            auth()->guard()->logout();
            $request->session()->invalidate();
            return redirect('/login')->withErrors(['bloqueio'=>'Login bloqueado, entre em contato com sua escola.']);
        }
    }

    public function alteraPermissao(Request $request)
    {
    	$ret = $this->usuarioService->alteraPermissao($request->all());
    	if($ret)
    	{
    		return redirect('catalogo');
    	}
    	else
    	{
    		return back();
    	}
    }
}
