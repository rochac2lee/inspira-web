<?php
namespace App\Http\Requests\Fr\Ead;

use App\Models\CicloEtapa;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class QuestaoRequest extends FormRequest
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
        Validator::extend('valida_alternativa', function($attribute, $value, $parameters, $validator) {
            if($parameters[1] == 'obj')
            {
                $numero = explode('_', $attribute);
                $numero = $numero[1];
                if($value!='' || $numero>$parameters[0])
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return true;
            }
        });

        Validator::extend('valida_qtd_linhas', function($attribute, $value, $parameters, $validator) {
            if($parameters[0] == 'dis')
            {
                if($value>0)
                {
                    return true;
                }
                else{
                    return false;
                }
            }
            else
            {
                return true;
            }
        });

        Validator::extend('valida_correta', function($attribute, $value, $parameters, $validator) {
            if($parameters[0] == 'obj')
            {
                if($value!='' && $value <= $parameters[1])
                {
                    return true;
                }
                else{
                    return false;
                }
            }
            else
            {
                return true;
            }
        });

        Validator::extend('valida_ciclo', function($attribute, $value, $parameters, $validator) {
            $ciclo = CicloEtapa::find($value);
            if($ciclo){
                if($ciclo->ciclo_id != 1 && $ciclo->ciclo_id != 5){
                    return true;
                }
            }
            return false;

        });

        $validacao = [
            'pergunta'          =>'required',
            'tipo'              =>'required',
            'qtd_alternativa'   =>'required|integer|between:1,7',
            'alternativa_1'     =>'valida_alternativa:'.$this->input('qtd_alternativa').','.$this->input('tipo'),
            'alternativa_2'     =>'valida_alternativa:'.$this->input('qtd_alternativa').','.$this->input('tipo'),
            'alternativa_3'     =>'valida_alternativa:'.$this->input('qtd_alternativa').','.$this->input('tipo'),
            'alternativa_4'     =>'valida_alternativa:'.$this->input('qtd_alternativa').','.$this->input('tipo'),
            'alternativa_5'     =>'valida_alternativa:'.$this->input('qtd_alternativa').','.$this->input('tipo'),
            'alternativa_6'     =>'valida_alternativa:'.$this->input('qtd_alternativa').','.$this->input('tipo'),
            'alternativa_7'     =>'valida_alternativa:'.$this->input('qtd_alternativa').','.$this->input('tipo'),
            'correta'           =>'valida_correta:'.$this->input('tipo').','.$this->input('qtd_alternativa'),
            'qtd_linhas'        =>'valida_qtd_linhas:'.$this->input('tipo'),
            'cicloetapa_id'     => 'required|valida_ciclo',
            'disciplina_id'     =>'required',
            'dificuldade'       =>'required',

        ];

        return $validacao;
    }

    public function attributes()
    {
        return[
            'disciplina_id'     =>'Componente curricular',
            'dificuldade'       =>'Dificuldade da Questão',
            'pergunta'          =>'Pergunta',
            'tipo'              =>'Tipo de Questão',
            'qtd_alternativa'   =>'Quantidade de Alternativas',
            'alternativa_1'     =>'Alternativa A',
            'alternativa_2'     =>'Alternativa B',
            'alternativa_3'     =>'Alternativa C',
            'alternativa_4'     =>'Alternativa D',
            'alternativa_5'     =>'Alternativa E',
            'alternativa_6'     =>'Alternativa F',
            'alternativa_7'     =>'Alternativa G',
            'correta'           =>'Alternativa Correta',
            'com_linhas'        =>'required',
            'qtd_linhas'        =>'Linhas para Resposta',
            'resolucao'         =>'Resolução da Resposta',
            'cicloetapa_id'     =>'Etapa / Ano',

        ];
    }

    public function messages()
    {
        return [
            'valida_alternativa' =>'Essa alternativa deve ser preenchida.',
            'valida_qtd_linhas' =>'A quantidade de linhas deve ser maior que 0.',
            'valida_resolucao' =>'A Resolução deve ser preenchida.',
            'valida_correta' =>'A alternativa correta deve ser selecionada, e ser uma alternativa existente.',
            'valida_ciclo' => 'O campo Etapa/Ano não pode pertencer aos ciclos 1 ou 5.',
        ];
    }
}
