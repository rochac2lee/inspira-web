<?php

namespace App\Http\Requests\Api\Agenda;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EnqueteRespostaRequest extends FormRequest
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
        return [
            'id'            => 'required|integer',
            'aluno_id'      => 'required|integer',
            'turma_id'      => 'required|integer',
            'resposta'    => 'required|between:1,7',
        ];
    }
}
