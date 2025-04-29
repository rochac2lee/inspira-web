<?php
namespace App\Http\Requests\Fr\Agenda;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class RegistroBuscarRequest extends FormRequest
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
        $retorno =  [
            'data'  =>'required',
            'turma_id' =>'required',
        ];

        if(auth()->user()->permissao == 'C'){
            $retorno['professor_id']  = 'required';
        }
        if(auth()->user()->permissao == 'I'){
            $retorno['escola_id']  = 'required';
            $retorno['professor_id']  = 'required';
        }
        return $retorno;
    }

    public function attributes()
    {
        return[
            'data'  =>'Data',
            'turma_id' =>'Turma',
            'professor_id' =>'Professor',
            'escola_id' =>'Escola',
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
        if($this->request->get('data') != '')
        {
            $tratar['data'] = dataUS($this->request->get('data'));
        }

        $this->merge($tratar);
    }

}
