<?php
namespace App\Http\Requests\Fr;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class QuizRequest extends FormRequest
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
            'titulo'        =>'required',
            'imagem'        =>'valida_imagem:'.$this->input('existeImg'),
            'disciplina_id' =>'required',
            //'colecao_livro_id' =>'valida_colecao',
            'ciclo_etapa_id'=>'required',
            'nivel'         =>'required',
            'qtd_tentativas'         =>'required',
            'pontuacao'      =>'required|integer|between:10,1000',

        ];

        return $validacao;
    }

    public function attributes()
    {
        return[
            'titulo'            =>'Título',
            'disciplina_id'     =>'Disciplina',
            'ciclo_etapa_id'    =>'Etapa / Ano',
            'nivel'             =>'Nível',
            'imagem'            =>'Capa do quiz',
            'colecao_livro_id'  =>'Coleção',
            'qtd_tentativas'    =>'Quantidade de tentativas',

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
