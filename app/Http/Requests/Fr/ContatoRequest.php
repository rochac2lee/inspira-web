<?php

namespace App\Http\Requests\Fr;

use Validator;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ReCaptcha;

class ContatoRequest extends FormRequest
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
            'nome'=>'required',
            'escola'=>'required',
            'cidade'=>'required',
            'telefone'=>'required',
            'email'=>'required|email',
            'assunto'=>'required',
            'msg'=>'required',
            'g-recaptcha-response'=>[new ReCaptcha],
        ];
    }

    public function attributes()
    {
        return[
            'nome'=>'Nome completo',
            'escola'=>'Escola',
            'cidade'=>'Cidade',
            'telefone'=>'Telefone',
            'email'=>'E-mail',
            'assunto'=>'Assunto',
            'msg'=>'Mensagem',
        ];
    }

    public function messages()
    {
        return [
            'valida_recaptcha'  => 'O Recaptcha não foi validado',
        ];
    }
}
