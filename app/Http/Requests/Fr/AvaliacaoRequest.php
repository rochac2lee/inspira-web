<?php
namespace App\Http\Requests\Fr;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


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

        Validator::extend('valida_liberacao_resultado', function($attribute, $value, $parameters, $validator) {
            if($parameters[0] == 'o'){
                $d = date('Y-m-d H:i:s', strtotime("+".$parameters[1]." minutes",strtotime($parameters[2])));
                $dataLimite = Carbon::createFromFormat('Y-m-d H:i:s', $d);
                $dataLiberacao = Carbon::createFromFormat('Y-m-d H:i:s', $value);

                return  $dataLiberacao->gte($dataLimite);
            }else{
                return true;
            }

        });

        $validacao = [
            'disciplina_id' =>'required',
            'tipo'          =>'required',
            'titulo'        =>'required',
            'aplicacao'     =>'required',
            'data_hora_inicial' =>'required|date',

            'tempo_maximo'  =>'required|integer|between:10,600',
            'questao'       =>'required',
        ];
        if($attributes['aplicacao']=='o')
        {
            $validacao['data_hora_final'] = 'required|date|after:'.$attributes['data_hora_inicial']. '|after:now';
        }

        if(isset($attributes['tipo_peso']) && $attributes['tipo_peso']!='')
        {
            $validacao['peso'] = 'required';
        }

        if(isset($attributes['ead']) && $attributes['ead']==1)
        {
            $validacao['data_hora_liberacao_resultado'] = 'required|date|after:'.$attributes['data_hora_inicial'].'|valida_liberacao_resultado:'.$attributes['aplicacao'].','.$attributes['tempo_maximo'].','.$attributes['data_hora_final'];
        }


        return $validacao;
    }

    public function attributes()
    {
        return[
            'disciplina_id' =>'Componente curricular',
            'tipo'          =>'Tipo',
            'titulo'        =>'Titulo',
            'data_hora_inicial' =>'Início da Avaliação',
            'data_hora_final' => 'Fim da Avaliação',
            'data_hora_liberacao_resultado' =>'Liberação do Resultado',
            'questao' =>'Questões',
        ];
    }

    public function getValidatorInstance()
    {
        $this->trataDados();
        return parent::getValidatorInstance();
    }

    protected function trataDados()
    {
        $tratar = [];
        if($this->request->get('data_hora_inicial') != '')
        {
            $tratar['data_hora_inicial'] = dataUS($this->request->get('data_hora_inicial'));
        }
        if($this->request->get('data_hora_final') != '')
        {
            $tratar['data_hora_final'] = dataUS($this->request->get('data_hora_final'));
        }
        if($this->request->get('data_hora_liberacao_resultado') != '')
        {
            $tratar['data_hora_liberacao_resultado'] = dataUS($this->request->get('data_hora_liberacao_resultado'));
        }
        $this->merge($tratar);
    }

    public function messages()
    {
        return [
            'valida_liberacao_resultado' =>'Liberação do Resultado necessita ser maior que o Fim da Avaliação somado com o Tempo máximo de execução da prova.',
        ];
    }

}
