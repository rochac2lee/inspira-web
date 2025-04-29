<?php
namespace App\Http\Requests\Fr;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class EscolaRequest extends FormRequest
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
            'instituicao_id'    =>'required',
            'titulo'            =>'required',
            //'numero_contrato'   =>'required',
            //'cnpj'              =>'required',
            'etapa_ano'         =>'required',
            //'cor_primaria'      =>'',
            //'url'               =>'required',
            //'facebook'          =>'',
            //'instagram'         =>'',
            //'youtube'           =>'',
            //'cep'               =>'required',
            //'uf'                =>'required',
            //'cidade'            =>'required',
            //'bairro'            =>'required',
            //'logradouro'        =>'required',
            //'numero'            =>'required',
            //'complemento'       =>'',
            'adm_escola'        =>'required',
            'adm_email'         =>'required|email',
        ];

        if(isset($attributes['radio_adm']) && $attributes['radio_adm'] == 'editar'){
            $validacao['adm_email'] ='required|email|unique:users,email,'.$attributes['idUserAdm'];
        }

        return $validacao;
    }

    public function attributes()
    {
        return[
            'instituicao_id'    =>'Instituição',
            'titulo'            =>'Nome da Escola',
            'numero_contrato'   =>'Contrato',
            'cnpj'              =>'CNPJ',
            'etapa_ano'         =>'Etapa / Ano',
            'cor_primaria'      =>'Cor da Plataforma',
            'url'               =>'Personalização da URL',
            'facebook'          =>'Facebook',
            'instagram'         =>'Instagram',
            'youtube'           =>'Youtube',
            'cep'               =>'CEP',
            'uf'                =>'UF',
            'cidade'            =>'Cidade',
            'bairro'            =>'Bairro',
            'logradouro'        =>'Endereço',
            'numero'            =>'Número',
            'complemento'       =>'Complemento',

            'adm_email'         =>'E-mail',
            'adm_escola'          =>'Nome do Responsável',
        ];
    }
}
