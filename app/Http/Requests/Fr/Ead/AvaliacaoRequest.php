<?php
namespace App\Http\Requests\Fr\Ead;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;


class AvaliacaoRequest extends FormRequest
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

        Validator::extend('quantidade_minima', function($attribute, $value, $parameters, $validator) {
            if(count($value) < $parameters[0]){
                return false;
            }
            return true;
        });

        $validacao = [
            'titulo'        =>'required',
            'tempo_maximo'  =>'required|integer|between:10,600',
            'questao'       =>'required|quantidade_minima:'.$attributes['quantidade_minima_questoes'],
            'tipo_peso' => 'required',
            'peso' => 'required|integer',
            'quantidade_minima_questoes' => 'required|integer',
        ];

        return $validacao;
    }

    public function attributes()
    {
        return[
            'tipo'          =>'Tipo',
            'titulo'        =>'Titulo',
            'data_hora_inicial' =>'Início da Avaliação',
            'data_hora_final' => 'Fim da Avaliação',
            'data_hora_liberacao_resultado' =>'Liberação do Resultado',
            'questao' =>'Questões',
            'tipo_peso' => 'Tipo Peso',
            'peso' => 'Peso',
            'quantidade_minima_questoes' => 'Quantidade mínima de questões',
        ];
    }

    public function messages()
    {
        return [
            'quantidade_minima' =>'Aquantidade de questões adicionadas na avaliação deve ser igual ou maior que o campo Quantidade de Questões',
        ];
    }

}
