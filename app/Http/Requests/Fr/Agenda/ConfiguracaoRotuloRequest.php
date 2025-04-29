<?php
namespace App\Http\Requests\Fr\Agenda;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class ConfiguracaoRotuloRequest extends FormRequest
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
            'titulo.*'    => 'required_with:cor.*',
            'cor.*'       => 'required_with:titulo.*',
        ];
        return $validacao;
    }

    public function attributes()
    {
        return[
            'titulo'=> 'Título',
            'cor'  => 'Cor',
        ];
    }

    public function messages(){
        return  [
            'titulo.*' => 'Título',
            'cor.*' => 'Cor',
        ];
    }
}
