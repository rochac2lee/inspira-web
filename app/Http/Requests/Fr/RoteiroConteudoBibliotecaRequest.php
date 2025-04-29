<?php
namespace App\Http\Requests\Fr;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class RoteiroConteudoBibliotecaRequest extends FormRequest
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
            'tipo'      =>'required',
            'aula_id'   =>'required',
            'curso_id'  =>'required',
            'conteudosIds' => 'required',
        ];

        return $validacao;
    }

    public function attributes()
    {
        return[
            'conteudosIds' => 'Conteúdo',
        ];
    }
}
