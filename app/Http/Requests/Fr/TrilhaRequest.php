<?php
namespace App\Http\Requests\Fr;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class TrilhaRequest extends FormRequest
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
        Validator::extend('valida_imagem', function($attribute, $value, $parameters, $validator) {
            if($value!='' || (isset($parameters[0]) &&  $parameters[0]!='') )
            {
                return true;
            }
            else
            {
                return false;
            }
        });

        $validacao = [
            'roteiros'        =>'required',
            'titulo'        =>'required',
            'disciplina_id' =>'required',
            'ciclo_etapa_id' =>'required',
        ];
        if(auth()->user()->permissao == 'Z') {
            if ($this->input('ead') == 1) {
                $validacao['carga_horaria'] = 'required';
                $validacao['avaliacao_id'] = 'required';
                if ($this->input('tipo_permissao_realizar') == 4) {
                    $validacao['instituicao'] = 'required';
                }
                $validacao['perfil_permissao_realizar'] = 'required';
            }
            if ($this->input('permite_biblioteca') == 1) {
                $validacao['tipo_permissao_biblioteca'] = 'required';
                if ($this->input('tipo_permissao_biblioteca') == 4) {
                    $validacao['instituicao'] = 'required';
                }
            }
        }

        if($this->input('existeImg') == '') {
            $validacao['imagem'] = 'required';
        }

        return $validacao;
    }

    public function attributes()
    {
        return[
            'titulo'            =>'Título',
            'disciplina_id'     =>'Componente curricular',
            'ciclo_etapa_id'    =>'Etapa / Ano',
            'imagem'            =>'Capa da trilha',
            'roteiros'          =>'Roteiros',
            'permite_biblioteca'    =>'Adicionar na biblioteca',
            'tipo_permissao_biblioteca' =>'Permissionamento',
            'instituicao_permissao_biblioteca'  =>'Permissão para a instituição',
            'tipo_permissao_realizar'  =>'Permissão para a instituição',
            'instituicao_permissao_realizar'  =>'Permissão para a instituição',
            'perfil_realizar'  =>'Permissão para perfil',
            'carga_horaria' => 'Carga Horária',
            'avaliacao_id' => 'Avaliação',
            'instituicao' => 'Instituições',

        ];
    }

    public function messages()
    {
        return [
            'valida_imagem' =>'A capa do álbum é obrigatória',
        ];
    }
}
