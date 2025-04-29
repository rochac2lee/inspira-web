<?php
namespace App\Http\Requests\Fr\Agenda;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class ConfiguracaoEstiloRequest extends FormRequest
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
            'titulo_inicial'    => 'required',
            'cor_primaria'  => 'required',
            'imagem'        => 'required_without:existeImg',
        ];
        return $validacao;
    }

    public function attributes()
    {
        return[
            'titulo_inicial'=> 'Título inicial',
            'cor_primaria'  => 'Cor primária',
            'imagem'        => 'Imagem',
        ];
    }

    public function messages(){
        return  [
            'imagem.required_without' => 'O campo Imagem é obrigatório.',
        ];
    }
}
