<?php

namespace App\Services\Api;

use App\Exceptions\LoginInvalidException;

class AuthService
{

    /**
     * @param $dados
     * @return array
     * @throws LoginInvalidException
     */
    public function login($dados)
    {
        $custom = ['sessao'=>null];
        if(!$token = auth('api')->claims($custom)->attempt($dados)){
            throw new LoginInvalidException();
        }

        return [
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL()
        ];
    }

    public function refresh($sessao = null){
        //$payload = auth('api')->getPayload();
        //dd($payload->get('teste'));
        $token = auth('api')->claims(['sessao'=>$sessao])->refresh();
        return [
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL()
        ];
    }
}
