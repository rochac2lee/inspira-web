<?php

namespace App\Http\Controllers\Fr;

use App\Http\Requests\AppInspira\GetTokenRequest;
use App\Http\Requests\AppInspira\LoginAppRequest;
use App\Models\UserLogAtividade;
use App\Services\Fr\LoginAppService;
use App\Services\Fr\UsuarioService;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginAppController extends Controller
{
    public function __construct(LoginAppService $loginAppService, UsuarioService $usuarioService)
    {
        $this->loginAppService = $loginAppService;
        $this->usuarioService = $usuarioService;
    }

    public function getToken(GetTokenRequest $request){
        $dados = $this->loginAppService->getToken($request->validated());
        return response()->json($dados);
    }

    public function logarNormal(LoginAppRequest $request){
        $user = User::where('token_app',  $request->input('chave'))
            ->where('validade_token_app',  '>=', date('Y-m-d'))
            ->first();
        if($user) {
            $request->session()->invalidate();
            if (Auth::check()) {
                auth()->guard()->logout();
            }
            auth()->login($user);
            $this->usuarioService->gravaSessaoUsuario();
            $this->log('opet');
        }
        return redirect('/catalogo');
    }

    public function refresh(Request $request){
        $user = User::where('token_app',  $request->input('chave'))
            ->where('validade_token_app',  '>=', date('Y-m-d'))
            ->first();
        if($user){
            $dados = $this->loginAppService->refreshToken($user->id);
            return response()->json($dados);
        }
        else{
            return response()->json(false,500);
        }
    }

    public function logarSocial(Request $request){
        $userSocial = Socialite::driver('google')->userFromToken($request->input('chave'));
        $user = User::where(['email' => $userSocial->getEmail()])->first();

        if($user) {
            auth()->login($user);
            $this->usuarioService->gravaSessaoUsuario();
            $this->log('opet');
        }
        return redirect('/catalogo');
    }

    public function logarSocialValido(Request $request){
        try{
            $userSocial = Socialite::driver('google')->userFromToken($request->input('chave'));
            $user = User::where(['email' => $userSocial->getEmail()])->first();

            if($user) {
                return response()->json(true);
            }
            else{
                return response()->json(false,500);
            }
        }
        catch (\Exception $e){
            return response()->json(false,500);
        }
    }

    private function log($tipo){
        if($tipo != ''){
            UserLogAtividade::create(['user_id'=>auth()->user()->id, 'tipo' => $tipo]);
        }
    }
}
