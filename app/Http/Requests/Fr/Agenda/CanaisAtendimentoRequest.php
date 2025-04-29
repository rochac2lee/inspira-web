<?php
namespace App\Http\Requests\Fr\Agenda;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class CanaisAtendimentoRequest extends FormRequest
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
            'nome'      => 'required',
            'cargo'     => 'required',
            'imagem'    => 'required_without:existeImg',
            'email'     => 'required_without:telefone',
            'escola'    => 'required',
        ];
        return $validacao;
    }

    public function attributes()
    {
        return[
            'nome'  => 'Nome',
            'cargo' => 'Cargo',
            'imagem'=> 'Imagem',
            'email'=> 'Email',
            'telefone'=> 'Telefone',
            'escola'=> 'Escolas selecionadas',
        ];
    }

    public function messages(){
        return  [
            'imagem.required_without' => 'O campo imagem é obrigatório.',
        ];
    }
}
