<?php
namespace App\Http\Requests\Fr\Agenda;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class EnqueteRequest extends FormRequest
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
        Validator::extend('valida_alternativa', function($attribute, $value, $parameters, $validator) {
                $numero = explode('_', $attribute);
                $numero = $numero[1];
                if($value!='' || $numero > $parameters[0])
                {
                    return true;
                }
                else
                {
                    return false;
                }
        });
        $validacao = [
            'pergunta'          =>'required',
            'qtd_alternativa'   =>'required|integer|between:1,10',
            'alternativa_1'     =>'valida_alternativa:'.$this->input('qtd_alternativa'),
            'alternativa_2'     =>'valida_alternativa:'.$this->input('qtd_alternativa'),
            'alternativa_3'     =>'valida_alternativa:'.$this->input('qtd_alternativa'),
            'alternativa_4'     =>'valida_alternativa:'.$this->input('qtd_alternativa'),
            'alternativa_5'     =>'valida_alternativa:'.$this->input('qtd_alternativa'),
            'alternativa_6'     =>'valida_alternativa:'.$this->input('qtd_alternativa'),
            'alternativa_7'     =>'valida_alternativa:'.$this->input('qtd_alternativa'),
            'aluno'             =>'required',
        ];

        return $validacao;
    }

    public function attributes()
    {
        return[
            'aluno' => 'Turmas e alunos',
            'descricao' => 'Descrição',
        ];
    }

    public function messages()
    {
        return [
            'valida_alternativa' =>'Essa alternativa deve ser preenchida.',
        ];
    }
}
