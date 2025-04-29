<?php

namespace App\Http\Controllers\Fr;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Plataforma;

class TermosController extends Controller
{
    use AuthenticatesUsers;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function aceitar(Request $request)
    {
        $plataforma = Plataforma::first();
        if($request->get('aceito') == 1){

           User::find(auth()->user()->id)->update([
                'terms' => $request->get('aceito')
            ]);
            $request->session()->regenerate();
            return redirect('/catalogo');
        }
        $rotas = Route::getCurrentRoute()->getName();
        $resposta = 'Para acessar o sistema você precisa aceitar os termos.';
        return view('fr/termo-aceite')->with(compact('resposta','plataforma'));
    }
}
