<?php

namespace App\Http\Requests\Fr;

use Illuminate\Foundation\Http\FormRequest;

class QRCodeRequest extends FormRequest
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
            'descricao' => 'required',
            'url' => 'required|url',
            'observacao' => 'nullable',
            'tipo_midia' => 'nullable',
            'disciplina' => 'required',
            'colecaoLivros' => 'nullable',
            'cicloEtapa' => 'required',
        ];
    }
    public function attributes()
    {
        return[
            'descricao'=>'Descrição do QRCode',
            'url'=>'URL de destino',
        ];
    }
}
