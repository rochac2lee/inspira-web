<?php
namespace App\Services\Fr;
use App\Exceptions\LoginInvalidException;
use App\Models\User;
use DB;
use Hash;
use Illuminate\Support\Facades\Auth;
use Validator;



class LoginAppService {
    public function getToken($dados)
    {
        $user = [
            'email'     => $dados['email'],
            'password'  => $dados['password'],
            ];
        if(!auth('web')->attempt($user)){
            throw new LoginInvalidException();
        }else{
            $token = $this->refreshToken(auth()->user()->id);

            if (Auth::check()) {
                auth()->guard()->logout();
            }
            return $token;
        }
    }

    public function refreshToken($idUser){
        $dados = [
            'token_app'=>$this->keygen(129),
            'validade_token_app'=> date('Y-m-d', strtotime(date('Y-m-d'). ' + 30 days'))
        ];
        $user = User::find($idUser);
        $user->update($dados);
        $dados['avatar'] = $this->avatar($user);
        $d['data'] = $dados;
        return (object) $d;
    }

    private function avatar($user)
    {
        if ($user->avatar_social != ''){
            return $user->avatar_social;
        }elseif($user->img_perfil!='') {
            return config('app.cdn').'/storage/uploads/usuarios/perfil/' . $user->img_perfil;
        }else{
            return config('app.cdn')."/fr/imagens/avatar-user.png";
        }
    }

    public function keygen($length=10)
    {
        $key = '';
        list($usec, $sec) = explode(' ', microtime());
        mt_srand((float) $sec + ((float) $usec * 100000));

        $inputs = array_merge(range('z','a'),range(0,9),range('A','Z'));

        for($i=0; $i<$length; $i++)
        {
            $key .= $inputs[mt_rand(0,61)];
        }
        return $key;
    }
}
