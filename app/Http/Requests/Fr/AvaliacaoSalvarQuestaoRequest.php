<?php
namespace App\Http\Requests\Fr;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;


class AvaliacaoSalvarQuestaoRequest extends FormRequest
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
        $attributes = parent::all();

        Validator::extend('valida_imagem', function($attribute, $value, $parameters, $validator) {
            if($value!='' || (isset($parameters[0]) &&  $parameters[0]!='') )
            {
                return true;
            }
            else
            {
                return false;
            }
        });

        Validator::extend('valida_colecao', function($attribute, $value, $parameters, $validator) {
            if(auth()->user()->permissao == 'Z' || auth()->user()->instituicao_id == 1)
            {
                if($value!='')
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        });

        $validacao = [
            'avaliacao'        =>'required',
        ];

        return $validacao;
    }

    public function attributes()
    {
        return[
            'avaliacao'         =>'Correção',


        ];
    }

    public function messages()
    {
        return [
            'valida_imagem' =>'A capa do quiz é obrigatória',
            'valida_colecao' =>'O campo coleção é obrigatório',
        ];
    }
}
