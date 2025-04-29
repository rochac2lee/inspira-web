<?php

namespace App\Http\Controllers\Fr;

use App\Http\Requests\Fr\GestaoUsuarioRequest;
use App\Models\Instituicao;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Fr\UsuarioService;

use Auth;

class GestaoUsuarioController extends Controller
{
	public function __construct(UsuarioService $usuarioService)
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if(\Illuminate\Support\Facades\Auth::user()->permissao != 'Z' )
            {
                return back();
            }
            return $next($request);
        });
        $this->usuarioService = $usuarioService;
    }

    public function index(Request $request){

	    $view = [
	        'dados' => $this->usuarioService->getListaGeral($request->all()),
	        'instituicao' => Instituicao::where('id','<>','1')->orderBy('titulo')->get(),
        ];
	    return view('fr/gestao/usuarios/lista',$view);
    }

    public function add(GestaoUsuarioRequest $request){

        $retorno = $this->usuarioService->add($request->all());

        if($retorno){
            return back()->with('certo', 'Usuário adicionado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar adicionar usuário.');
        }
    }

    public function getAjax(Request $request)
    {
        $retorno = $this->usuarioService->getForm($request->input('id'));
        if($retorno){
            return response()->json($retorno);
        }
        else
        {
            return response()->json( $retorno, 400 );
        }
    }

    public function editar(GestaoUsuarioRequest $request){

        $retorno = $this->usuarioService->editar($request->all());

        if($retorno){
            return back()->with('certo', 'Usuário alterado.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar alterar o usuário.');
        }
    }

    public function excluir($id)
    {
        $retorno = $this->usuarioService->excluir($id);

        if($retorno){
            return back()->with('certo', 'Usuário excluído.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar excluir usuário.');
        }
    }

    public function logar(Request $request, $professorId)
    {
        $user = User::find($professorId);
        auth()->guard()->logout();
        $request->session()->invalidate();
        auth()->login($user);
        $this->usuarioService->gravaSessaoUsuario();
        return redirect('/');

    }

    public function novaSenha($id)
    {
        $retorno = $this->usuarioService->novaSenha($id);

        if($retorno){
            return back()->with('certo', 'Nova senha gerada.');
        }
        else{
            return back()->with('erro', 'Erro ao tentar gerar nova senha.');
        }

    }
    public function getEscolas(Request $request)
    {
        $retorno = $this->usuarioService->getEscolas($request->all());
        if($retorno!==false){
            return response()->json( $retorno, 200 );
        }
        else
        {
            return response()->json( $retorno, 400 );
        }

    }

}
