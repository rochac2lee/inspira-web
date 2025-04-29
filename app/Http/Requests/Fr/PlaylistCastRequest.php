<?php
namespace App\Http\Requests\Fr;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class PlaylistCastRequest extends FormRequest
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
            'lista_audio'   =>'required',

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
            'disciplina_id'     =>'Disciplina',
            'ciclo_etapa_id'    =>'Etapa / Ano',
            'categoria_id'      =>'Categoria',
            'imagem'            =>'Capa do áudio',
            'lista_audio'       =>'Áudios',

        ];
    }

    public function messages()
    {
        return [
            'valida_imagem' =>'A capa da playlist é obrigatória',
        ];
    }
}
