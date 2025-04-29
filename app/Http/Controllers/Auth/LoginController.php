<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;

use App\Services\Fr\UsuarioService;

use App\Rules\ReCaptcha;
use App\Rules\LoginComInstituicaoAtiva;
use App\Rules\LoginUsuarioAtivo;
use Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $usuarioService;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/catalogo';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;
        $this->middleware('guest')->except('logout');
    }


    protected function authenticated(Request $request, $user)
    {
        $this->usuarioService->gravaSessaoUsuario();

        if (auth()->user()->permissao == "A") {
            return redirect('/catalogo');
        } else {
            return redirect('/catalogo');
        }
    }

    protected function validateLogin(Request $request)
    {
        $validacao = [
            'email' => ['required', 'email', new LoginComInstituicaoAtiva, new LoginUsuarioAtivo],
            'password' => 'required|min:6',
        ];
        Log::info('Validando login', ['request' => $request->all()]);
        if (env('APP_ENV') != 'local') {
            $validacao['g-recaptcha-response'] = [new ReCaptcha];
        }
        $this->validate($request, $validacao);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect(route('login'));
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return auth()->guard();
    }

}
