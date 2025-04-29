<?php
namespace App\Http\Requests\Fr\Agenda;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class ComunicadoRequest extends FormRequest
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
            'visualizacao'  =>'required',
            'titulo'        =>'required',
            'descricao'     =>'required',
            'escola'        => 'required',
        ];
        return $validacao;
    }

    public function attributes()
    {
        return[
            'titulo' => 'Título',
            'descricao' => 'Descrição',
            'escola' => 'Escolas e turmas selecionadas',
        ];
    }
}
