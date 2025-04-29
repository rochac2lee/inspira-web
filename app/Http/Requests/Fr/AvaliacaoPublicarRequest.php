<?php
namespace App\Http\Requests\Fr;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class AvaliacaoPublicarRequest extends FormRequest
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
        $validacao =[];
        if(auth()->user()->permissao == 'P' || (auth()->user()->permissao == 'C' && $attributes['tipo']==2) ) {
            $validacao = [
                'turmas' => 'required',
            ];
        }
        return $validacao;
    }

    public function attributes()
    {
        return[
            'turmas' => 'Turma',
        ];
    }



}
