<?php
namespace App\Http\Requests\Fr\Agenda;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class ConfiguracaoRegistrosRotinasTurmaRequest extends FormRequest
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
            'escola_id'      => 'required',
            'turma_id'    => 'required',
        ];
        return $validacao;
    }

    public function attributes()
    {
        return[
            'escola_id'=> 'Escola',
            'turma_id'=> 'Turma',
        ];
    }

}
