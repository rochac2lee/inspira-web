<?php
namespace App\Http\Requests\Fr\Agenda;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class CalendarioRequest extends FormRequest
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
            'data_inicial'  => 'required',
            'data_final'    => 'required',
            'tema'          => 'required',
        ];

        if(isset($attributes['visualizacao']) && $attributes['visualizacao'] == 2){
            $validacao['escola'] = 'required';
        }

        return $validacao;
    }

    public function attributes()
    {
        return[
            'titulo'        => 'Título',
            'descricao'     => 'Descrição',
            'escola'        => 'Escolas',
            'data_inicial'  => 'Data Inicial',
            'data_final'    => 'Data Final',
            'tema'          => 'Tema',
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
        if($this->request->get('data_inicial') != '')
        {
            $tratar['data_inicial'] = dataUS($this->request->get('data_inicial'));
        }
        if($this->request->get('data_final') != '')
        {
            $tratar['data_final'] = dataUS($this->request->get('data_final'));
        }
        $this->merge($tratar);
    }
}
