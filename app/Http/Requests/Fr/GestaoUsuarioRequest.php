<?php
namespace App\Http\Requests\Fr;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class GestaoUsuarioRequest extends FormRequest
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

        return  [
            'nome_completo'      =>'required',
            'email'     =>'required|email',
            'permissao' =>'required',
            'escola_id' =>'required',
            'instituicao_id' =>'required',
        ];

    }

    public function attributes()
    {
        return[
            'nome_completo'      =>'Nome',
            'email'     =>'E-mail',
            'permissao' =>'Permissão',
            'escola_id' =>'Escola',
            'instituicao_id' =>'Instituicao',
        ];
    }

}
