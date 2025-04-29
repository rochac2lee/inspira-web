<?php

namespace App\Http\Middleware;

use App\Models\Escola;
use App\Models\Instituicao;
use Closure;
use App\Models\UserPermissao;

class Aceite
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (auth()->check() ){
            $user = auth()->user();
            if ($user->terms != 1){
                return redirect('termo-aceite');
            }
            else
            {
                ///// verifica multi permissoes
                $multi = session('multiPermissoesEscolhido');
                if($multi!=1)
                {
                    $permi = UserPermissao::where('user_permissao.user_id',$user->id)->get();
                    if(count($permi)>0 || auth()->user()->permissao == 'R' || auth()->user()->permissao == 'I')
                    {
                        return redirect('multiplasPermissoes');
                    }
                    else
                    {
                        $escola = Escola::where('status_id',1)->find(auth()->user()->escola_id);
                        $instituicao = Instituicao::where('status_id',1)->find(auth()->user()->instituicao_id);
                        if(!$escola || !$instituicao){
                            auth()->guard()->logout();
                            $request->session()->invalidate();
                            return redirect('/login')->withErrors(['bloqueio'=>'Login bloqueado, entre em contato com sua escola.']);
                        }
                        session(['multiPermissoesEscolhido'=>1]);
                    }
                }

            }
        }


        return $next($request);
    }
}
