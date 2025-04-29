<?php

namespace App\Http\Controllers\Fr;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Services\Fr\UsuarioService;
use App\Http\Controllers\Controller;

class SocialAuthController extends Controller
{
    public function __construct(UsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;
    }
    public function redirectToSocial()
    {
        return Socialite::with('google')->redirect();
    }

    function handleSocialCallback()
    {
        try {
            $userSocial = Socialite::driver('google')->stateless()->user();
            $user = User::where(['email' => $userSocial->getEmail()])->first();
            if($user){
                if($user->avatar_social == '')
                {
                    $avatar = ['avatar_social'=>$userSocial->getAvatar()];
                    $user->update($avatar);
                }
               auth()->login($user);
               $this->usuarioService->gravaSessaoUsuario();
               return redirect('/');
            }else
            {
                redirect('/')->with('erro', 'Usuário google não encontrado na base de dados.');
            }
        } catch (\Exception $e) {
            redirect('/')->with('erro', 'Essa credencial não é válida!. Você deve usar um e-mail @opeteducation ou @souopet ativo.');
        }
    }
}
