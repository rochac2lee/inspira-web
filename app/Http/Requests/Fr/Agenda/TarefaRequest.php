<?php
namespace App\Http\Requests\Fr\Agenda;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class
TarefaRequest extends FormRequest
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
            'disciplina_id' => 'required',
            'data_entrega'=> 'required|after:now',
            'aluno' => 'required',
        ];
        return $validacao;
    }

    public function attributes()
    {
        return[
            'titulo' => 'Título',
            'descricao' => 'Descrição',
            'disciplina_id' => 'Componente curricular',
            'data_entrega' => 'Data de entrega',
            'aluno' => 'Turmas e estudantes',
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
        if($this->request->get('data_entrega') != '')
        {
            $tratar['data_entrega'] = dataUS($this->request->get('data_entrega'));
        }

        $this->merge($tratar);
    }
}
