<?php
namespace App\Http\Requests\Fr;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class QuizPerguntaTipo1Request extends FormRequest
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
            $pos = explode('.', $attribute);
            $pos = $pos[1];
            $existeImagem = $this->input('existeImg');
            $imagem = $this->input('imagem');
            if($imagem[$pos] || $existeImagem[$pos])
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
            'sub_titulo'        =>'required',
            'audio_titulo'  =>'mimes:mp3',
            ///
            'imagem.*'       =>'valida_imagem',
            //'titulo_alternativa.*' =>'required',
           // 'audio_alternativa1'  =>'mimes:application/octet-stream,audio/mp3',
           // 'audio_alternativa2'  =>'mimes:application/octet-stream,audio/mp3',
           // 'audio_alternativa3'  =>'mimes:application/octet-stream,audio/mp3',
           // 'audio_alternativa4'  =>'mimes:application/octet-stream,audio/mp3',
            'correta' =>'required',
        ];

        return $validacao;
    }

    public function attributes()
    {
        return[
            'titulo'        =>'Titulo da pergunta',
            'audio_titulo'  =>'Audio da pergunta',
            'sub_titulo'  =>'Pergunta',
            'imagem.*'       =>'Imagem da alternativa',
            'titulo_alternativa.*' =>'Texto da alternativa',
            'audio_alternativa.*'  =>'Áudio da alternativa',
        ];
    }

    public function messages()
    {
        return [
            'valida_imagem' =>'O campo Imagem é obrigatório',
        ];
    }
}
