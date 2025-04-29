<?php
namespace App\Http\Requests\Fr;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class InstituicaoRequest extends FormRequest
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
            'titulo'                =>'required',
            'instituicao_tipo_id'   =>'required',
            'adm_email'             =>'required|email',
            'adm_inst'              =>'required',
            'cor_primaria'          =>'required',
            'cor_secundaria'        =>'required',
        ];
        if(isset($attributes['radio_adm']) && $attributes['radio_adm'] == 'editar'){
            $validacao['adm_email'] ='required|email|unique:users,email,'.$attributes['idUserAdm'];
        }
        
        return $validacao;
    }

    public function attributes()
    {
        return[
            'titulo'                =>'Nome da Instituição',
            'instituicao_tipo_id'   =>'Tipo da Instituição',
            'descricao'             =>'Descrição',
            'cor_primaria'          =>'Cor Primária',
            'cor_secundaria'        =>'Cor Secundária',
            'adm_email'             =>'E-mail do administrador da instituição',
            'adm_inst'              =>'Nome do administrador da instituição',
        ];
    }
}
