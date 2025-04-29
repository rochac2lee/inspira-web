<?php
namespace App\Http\Requests\Fr;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class ImportacaoProfessorRequest extends FormRequest
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
            'tipo_arquivo'  =>'required',
            'arquivo'       =>'required',
        ];
        
        return $validacao;
    }

    public function attributes()
    {
        return[
            'tipo_arquivo'  =>'Formato do arquivo',
            'arquivo'       =>'Arquivo',
        ];
    }
}
