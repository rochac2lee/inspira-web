<?php
namespace App\Http\Requests\Fr\Indica;

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

        Validator::extend('valida_liberacao_resultado', function($attribute, $value, $parameters, $validator) {
                $d = date('Y-m-d H:i:s', strtotime("+".$parameters[0]." minutes",strtotime($parameters[1])));
                $dataLimite = Carbon::createFromFormat('Y-m-d H:i:s', $d);
                $dataLiberacao = Carbon::createFromFormat('Y-m-d H:i:s', $value);

                return  $dataLiberacao->gte($dataLimite);
        });

        $validacao = [
            'disciplina_id' =>'required',
            'titulo'        =>'required',
            'cicloetapa_id'     =>'required',
           // 'instituicao'     =>'required',
            'data_hora_inicial' =>'required|date',
            'data_hora_final' =>'required|date|after:'.$attributes['data_hora_inicial'],
            'tempo_maximo'  =>'required|integer|between:10,600',
            'questao'       =>'required',
            'escola'        => 'required',
            'caderno'        => 'required',
        ];

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
            'cicloetapa_id' =>'Etapa/Ano',
            'instituicao' =>'Instituição',
            'escola' =>'Instituições e escolas',
            'caderno' =>'Caderno',
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
