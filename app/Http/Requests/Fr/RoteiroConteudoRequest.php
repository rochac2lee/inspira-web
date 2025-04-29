<?php
namespace App\Http\Requests\Fr;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class RoteiroConteudoRequest extends FormRequest
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
        $validacao = [
            'tipo'        =>'required',
            'aula_id'        =>'required',
            'curso_id'        =>'required',
            'titulo'        =>'required',
           // 'descricao'     =>'required',
           // 'disciplina_id' =>'required',
           // 'ciclo_etapa_id' =>'required',
        ];
        $tipo = $this->input('tipo');
        if($tipo == 1 || $tipo == 7) {
            $validacao['conteudo_1'] = 'required';
        }elseif($tipo == 2 || $tipo == 3 || $tipo == 4 || $tipo == 15){
            $tamanho = '';
            if($this->input('existe_arquivo')!=1) {
                if($tipo == 2 || $tipo == 3 || $tipo == 4)
                {
                    $tamanho= '|max:13000';
                }
                if($tipo == 6)
                {
                    $tamanho= '|max:55000';
                }
                $validacao['conteudo_' . $tipo] = 'required_without:link_' . $tipo.$tamanho;
                $validacao['link_' . $tipo] = 'nullable|url';
            }
        }elseif($tipo == 8){
            $validacao['conteudo_8'] = 'required';
            $validacao['alternativa_1'] = 'required';
            $validacao['alternativa_2'] = 'required';
            $validacao['alternativa_3'] = 'required';
            $validacao['correta'] = 'required';
        }

        return $validacao;
    }

    public function attributes()
    {
        return[
            'titulo'            =>'Título',
            'descricao'         =>'Descrição',
            'disciplina_id'     =>'Componente curricular',
            'ciclo_etapa_id'    =>'Etapa / Ano',
            'conteudo_1'        =>'Conteúdo',
            'conteudo_2'        =>'Arquivo de áudio',
            'link_2'            =>'Link do áudio',
            'conteudo_8'        =>'Pergunta',
            'alternativa_1'        =>'Alternativa 1',
            'alternativa_2'        =>'Alternativa 2',
            'alternativa_3'        =>'Alternativa 3',
            'correta'        =>'Alternativa Correta',
        ];
    }

    public function messages()
    {
        return [
            'valida_imagem' =>'A capa do álbum é obrigatória',
        ];
    }
}
