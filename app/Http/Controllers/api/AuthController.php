<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthLoginRequest;
use App\Http\Requests\Api\AuthLoginSocialRequest;
use App\Http\Requests\Api\TrocarLoginRequest;
use App\Http\Requests\Api\TrocaSenhaRequest;
use App\Http\Resources\UserPermissaoResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserLogAtividade;
use App\Services\Api\AuthService;
use App\Services\Fr\UsuarioService;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    private $authService;
    private $usuarioService;

    public function __construct(AuthService $authService, UsuarioService $usuarioService){
        $this->middleware('auth:api')->only(['refresh', 'logout', 'usuario', 'permissoes', 'deviceKey', 'trocaSenha']);
        $this->authService = $authService;
        $this->usuarioService = $usuarioService;
    }

    /**
     * @param AuthLoginRequest $request
     * @return UserResource
     * @throws \App\Exceptions\LoginInvalidException
     */
    public function login(AuthLoginRequest $request)
    {
        $dados =$request->validated();
        $token =  $this->authService->login($dados);
        $this->log($request->input('tipo'));
        return (new UserResource(auth('api')->user()))->additional($token);
    }

    public function alteraPermissao(TrocarLoginRequest $request)
    {
        $dados =$request->validated();
        try {
            $retorno =  $this->usuarioService->alteraPermissao($dados);
        } catch (\Exception $e) {
            $retorno = false;
        }
        if($retorno){
            $token = $this->authService->refresh($dados);
            return response()->json($token);
        }else{
            return response()->json(['message' => 'Erro ao alterar o login.'], 500);
        }
    }

    public function loginSocial(AuthLoginSocialRequest $request)
    {
        try {
            $userSocial = Socialite::driver('google')->userFromToken($request->input('chave'));
            $user = User::where(['email' => $userSocial->getEmail()])->first();
            if($user){
                if($user->avatar_social == '')
                {
                    $avatar = ['avatar_social'=>$userSocial->getAvatar()];
                    $user->update($avatar);
                }
                auth('api')->login($user);
                $token = $this->authService->refresh();
                $this->log($request->input('tipo'));
                return (new UserResource(auth('api')->user()))->additional($token);

            }else
            {
                return response()->json(['message' => 'Usuário google não encontrado na base de dados.'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Não autorizado pelo Google. ', 'error'=>$e->getMessage()], 500);
        }
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->usuarioService->setaDeviceKey($request->input('tipo'), null);
        auth('api')->logout();
        return response()->json(['message' => 'deslogado']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        $token = $this->authService->refresh();
        $this->log($request->input('tipo'));
        return (new UserResource(auth('api')->user()))->additional($token);
    }

    public function usuario(){
        return (new UserResource(auth('api')->user()));
    }

    public function permissoes(){
        return (new UserPermissaoResource(auth('api')->user()));
    }

    public function deviceKey(Request $request){
        $status = $this->usuarioService->setaDeviceKey($request->input('tipo'), $request->input('device_key'));
        if($status){
            return response()->json(true);
        }else{
            return response()->json(false, 500);
        }
    }

    public function trocaSenha(TrocaSenhaRequest $request){
        $dados = $this->usuarioService->trocaSenha($request->input('password'));
        if($dados===true){
            return response()->json(['message'=>true]);
        }else{
            return response()->json(['message'=>$dados],500);
        }
    }

    private function log($tipo){
        if($tipo != ''){
            UserLogAtividade::create(['user_id'=>auth('api')->user()->id, 'tipo' => $tipo]);
        }
    }
}
