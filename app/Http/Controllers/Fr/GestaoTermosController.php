<?php

namespace App\Http\Controllers\Fr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;

use App\Models\Plataforma;
use Auth;

class GestaoTermosController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if(Auth::user()->permissao != 'Z' )
            {
                return back();
            }
            return $next($request);
        });
    }

    public function form()
    {
    	$view = [
            'plataforma' => Plataforma::first(),
    	];
        return view('fr/gestao_plataforma/termos/form',$view);
    }

    public function update(Request $request)
    {
        $plataforma = Plataforma::first();
        $dados = $plataforma->update($request->all());
        if($dados)
        {
            return back()->with('certo', 'Termos alterados.');
        }else{
            return back()->with('erro', 'Erro ao tentar alterar termos.');
        }
    }

}
