<?php
namespace App\Http\Requests\Fr\Agenda;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class DocumentoRequest extends FormRequest
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
            'titulo'        =>'required',
            'descricao'     =>'required',
            'aluno'         =>'required',
        ];
        if($attributes['tipo'] == 2){
            if(!isset($attributes['ja_tem_arquivo']) || $attributes['ja_tem_arquivo'] ==  '' ){
                $validacao['arquivo'] = 'required';
            }
        }
        return $validacao;
    }

    public function attributes()
    {
        return[
            'titulo' => 'Título',
            'descricao' => 'Descrição',
        ];
    }

}
