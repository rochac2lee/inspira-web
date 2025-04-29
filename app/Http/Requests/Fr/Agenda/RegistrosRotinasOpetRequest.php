<?php
namespace App\Http\Requests\Fr\Agenda;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class RegistrosRotinasOpetRequest extends FormRequest
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
        $validacao = [
            'titulo'      => 'required',
            'imagem'    => 'required_without:existeImg',
            'rotina'    => 'required',

        ];
        return $validacao;
    }

    public function attributes()
    {
        return[
            'imagem'=> 'Imagem',
            'titulo'=> 'Título',
            'rotina'=> 'Rotina',
        ];
    }

    public function messages(){
        return  [
            'imagem.required_without' => 'O campo imagem é obrigatório.',
        ];
    }
}
