<?php

namespace App\Http\Controllers\Fr;

use App\Http\Requests\Fr\ResponsavelRequest;
use App\Services\Fr\EscolaService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;

use App\Services\Fr\ResponsavelService;

use App\Models\Escola;

class ResponsavelController extends Controller
{
    public function __construct( ResponsavelService $responsavelService, EscolaService $escolaService)
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if(Auth::user()->permissao != 'Z' && Auth::user()->permissao != 'I' && Auth::user()->permissao != 'C')
            {
                return back();
            }
            return $next($request);
        });
        $this->responsavelService     = $responsavelService;
        $this->escolaService     = $escolaService;
    }

    public function index($idEscola, Request $request)
    {
        if (!$this->escolaEhDaInstituicao($idEscola)){
            return back();
        }
    	$view = [
                'dados' => $this->responsavelService->getLista($idEscola,$request->all()),
                'escola'=> Escola::find($idEscola),
                'idEscola'=> $idEscola,
            ];
        return view('fr/gestao/escola/responsaveis/lista',$view);
    }

    public function novo($idEscola)
    {
        if (!$this->escolaEhDaInstituicao($idEscola)){
            return back();
        }
        $view = [
            'escola'=> Escola::find($idEscola),
        ];
        return view('fr/gestao/escola/responsaveis/form',$view);
    }

    public function add(ResponsavelRequest $request)
    {
        if (!$this->escolaEhDaInstituicao($request->input('escola_id'))){
            return back();
        }
        $retorno = $this->responsavelService->inserir($request->all());

        if($retorno===true){
            return redirect('gestao/escola/'.$request->input('escola_id').'/responsaveis')->with('certo', 'Responsável cadastrado.');
        }
        else{
            return redirect('gestao/escola/'.$request->input('escola_id').'/responsaveis')->with('erro', 'Erro ao tentar cadastrar responsável.');
        }
    }

    public function get($idEscola, $idResponsavel)
    {
        if (!$this->escolaEhDaInstituicao($idEscola)){
            return back();
        }
        $dados = $this->responsavelService->get($idEscola, $idResponsavel);

        $view = [
            'escola'=> Escola::find($idEscola),
            'dados' => $dados,
        ];
        return view('fr/gestao/escola/responsaveis/form',$view);
    }

    public function editar(ResponsavelRequest $request)
    {
        if (!$this->escolaEhDaInstituicao($request->input('escola_id'))){
            return back();
        }
        $retorno = $this->responsavelService->editar($request->all());

        if($retorno===true){
            return redirect('gestao/escola/'.$request->input('escola_id').'/responsaveis')->with('certo', 'Responsável editado.');
        }
        else{
            return redirect('gestao/escola/'.$request->input('escola_id').'/responsaveis')->with('erro', 'Erro ao tentar editar responsável.');
        }
    }

    public function excluir($idEscola, $idResponsavel)
    {
        if (!$this->escolaEhDaInstituicao($idEscola)){
            return back();
        }
        $retorno = $this->responsavelService->excluir($idEscola, $idResponsavel);
        if($retorno===true){
            return back()->with('certo', 'Responsável excluído.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir responsável.');
        }
    }

    private function escolaEhDaInstituicao($idEscola){
        if(auth()->user()->permissao == 'I'){
            $pode = $this->escolaService->escolaEhDaInstituicao($idEscola);
            if(!$pode){
                return false;
            }
        }elseif(auth()->user()->permissao == 'C'){
            if($idEscola != auth()->user()->escola_id){
                return false;
            }
        }
        return true;
    }

}
