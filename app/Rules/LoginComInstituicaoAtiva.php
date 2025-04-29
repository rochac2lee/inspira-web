<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Models\User;
use App\Models\UserPermissao;
use App\Models\Instituicao;
use App\Models\Escola;

class LoginComInstituicaoAtiva implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        $user = User::where('email',$value)->first();
        if($user != null)
        {
            $perm = UserPermissao::where('user_permissao.user_id',$user->id)
                                    ->get();
            if($perm!=null && count($perm)>0)
            {
                /*foreach($perm as $p)
                {
                    $escola = Escola::where('status_id',1)->find($p->escola_id);
                    if($escola == null)
                    {
                        dd(1);
                        return false;
                    }
                }*/
                return true;
            }
            else
            {
                $inst = Instituicao::where('id',$user->instituicao_id)
                                    ->where('status_id',1)
                                    ->first();
                if($inst!=null)
                {
                    return true;
                }
                else
                {
                    $escola = Escola::where('status_id',1)->find($user->escola_id);
                    //dd($escola);
                    $inst = Instituicao::where('id',$user->instituicao_id)
                                    ->where('status_id',1)
                                    ->first();
                    if($user->permissao == 'I' || $user->permissao == 'R')
                    {
                        if($inst!=null )
                        {
                            return true;
                        }
                        else
                        {
                            return false;
                        }
                    }else{
                        if($inst && $escola)
                        {
                            return true;
                        }
                        else
                        {
                            return false;
                        }
                    }


                }
            }
        }
        else
        {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Infelizmente a sua Unidade está com o acesso bloqueado na plataforma INspira! Por favor entre em contato com o responsável da sua escola para regularizar a situação.';
    }
}
