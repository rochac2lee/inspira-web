<?php
namespace App\Http\Requests\Fr;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class QuizPerguntaTipo4Request extends FormRequest
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
            'titulo_tipo4'      =>'required',
            'sub_titulo_tipo4'  =>'required',
            'qtda_alternativa'  =>'required',
            'corretaTipo4'      =>'required',
        ];
        
        return $validacao;
    }

    public function attributes()
    {
        return[
            'titulo_tipo4'      =>'Título da pergunta',
            'sub_titulo_tipo4'  =>'Pergunta',
            'qtda_alternativa'  => 'Quantidade de alternativas',
            'corretaTipo4'      => 'Alternativa correta'
        ];
    }
}
