<?php

namespace App\Http\Requests\Fr;

use Validator;
use Illuminate\Foundation\Http\FormRequest;
use GuzzleHttp\Client;
use App\Rules\ReCaptcha;
use App\Rules\LoginComInstituicaoAtiva;
use App\Rules\LoginUsuarioAtivo;

class EsqueciSenhaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'=>['required','email', new LoginComInstituicaoAtiva, new LoginUsuarioAtivo],
            'g-recaptcha-response'=>[new ReCaptcha],
        ];
    }

    public function messages()
    {
        return [
            'valida_recaptcha'  => 'O Recaptcha não foi validado',
        ];
    }
}
