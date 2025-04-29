<?php

namespace App\Http\Controllers\Fr\Agenda;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fr\Agenda\NoticiaRequest;
use App\Services\Fr\Agenda\NoticiaService;
use App\Services\Fr\EscolaService;
use Illuminate\Http\Request;

class RelatorioAcessoController extends Controller
{
    public function __construct( EscolaService $escolaService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->permissao != 'I') {
                return redirect('/');
            }
            return $next($request);
        });

        $this->escolaService = $escolaService;
    }

    public function index(Request $request){

        $view = [
            'dados' => $this->noticiaService->lista($request->all()),
        ];
        return view('fr.agenda.noticias.lista',$view);
    }

}
