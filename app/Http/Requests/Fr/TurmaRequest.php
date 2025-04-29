<?php
namespace App\Http\Requests\Fr;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class TurmaRequest extends FormRequest
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

        $validacao = [
            'titulo'            =>'required',
            'turno'            =>'required',
            'ciclo_etapa_id'    =>'required',
            'professor'         =>'required',
            'aluno'             =>'required',
        ];

        return $validacao;
    }

    public function attributes()
    {
        return[
            'titulo'            =>'Título',
            'turno'            =>'Turno',
            'ciclo_etapa_id'    =>'Etapa / Ano',
            'professor'         =>'Professores',
            'aluno'             =>'Alunos',

        ];
    }
}
