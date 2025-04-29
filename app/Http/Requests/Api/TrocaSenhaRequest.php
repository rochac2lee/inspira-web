<?php
namespace App\Http\Requests\Api;

use Auth;
use Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class TrocaSenhaRequest extends FormRequest
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
        Validator::extend('valida_atual', function($attribute, $value, $parameters, $validator) {
            return  Hash::check($value, Auth::user()->password);
        });


        $validacao = [
            'current_password' => 'required|valida_atual',
            'password' => 'required|string|min:6',

        ];

        return $validacao;
    }

    public function attributes()
    {
        return[
            'current_password'  =>'Senha atual',
            'password'          =>'Nova senha',

        ];
    }

    public function messages()
    {
        return [
            'valida_atual' =>'A senha atual não está correta.',
        ];
    }
}
