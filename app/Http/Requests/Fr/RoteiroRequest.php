<?php
namespace App\Http\Requests\Fr;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class RoteiroRequest extends FormRequest
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

        $validacao = [
            'titulo'        =>'required',
            'disciplina_id' =>'required',
            'ciclo_etapa_id' =>'required',
        ];


        if($this->input('existeImg') == '') {
            $validacao['imagem'] = 'required';
        }

        return $validacao;
    }

    public function attributes()
    {
        return[
            'titulo'            =>'Título',
            'disciplina_id'     =>'Componente curricular',
            'ciclo_etapa_id'    =>'Etapa / Ano',
            'imagem'            =>'Capa do roteiro',

        ];
    }

    public function messages()
    {
        return [
            'valida_imagem' =>'A capa do álbum é obrigatória',
        ];
    }
}
