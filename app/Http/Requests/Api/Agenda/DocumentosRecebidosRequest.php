<?php

namespace App\Http\Requests\Api\Agenda;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentosRecebidosRequest extends FormRequest
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
            'id'  => ['required','integer', Rule::exists('fr_agenda_documentos')->where(function ($query) {
                                                                $query->where('publicado',1)
                                                                    ->whereNull('arquivo');
                                                        }),
                                ],
            'aluno_id'      => 'required|integer',
            'turma_id'      => 'required|integer',
            'arquivo'       => 'required',
        ];
    }
}
