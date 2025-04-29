<?php
namespace App\Http\Requests\Fr;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class QuizPerguntaTipo3Request extends FormRequest
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
            'titulo_tipo3'      =>'required',
            'sub_titulo_tipo3'      =>'required',
            'audio_titulo_tipo3'  =>'mimes:mp3',
            'imagem_tipo3'      =>'valida_imagem:'.$this->input('existeImgTipo3'),
            'resposta_tipo3.*'   =>'required',
        ];

        return $validacao;
    }

    public function attributes()
    {
        return[
            'titulo_tipo3'        =>'Titulo da pergunta',
            'sub_titulo_tipo3'  =>'Pergunta',
            'audio_titulo_tipo3'  =>'Audio da pergunta',
            'imagem_tipo3'       =>'Imagem',
            'frase_correta'     =>'Frase correta',
            'frase_embaralhada' =>'Frase embaralhada',
        ];
    }

    public function messages()
    {
        return [
            'valida_imagem' =>'O campo Imagem é obrigatório',
        ];
    }
}
