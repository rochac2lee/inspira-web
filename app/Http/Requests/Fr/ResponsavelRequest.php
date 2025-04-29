<?php
namespace App\Http\Requests\Fr;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class ResponsavelRequest extends FormRequest
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
        $user_id = 0;
        if(isset($attributes['id'])){
            $user_id = $attributes['id'];
        }
        $alunos = serialize([]);
        if(isset($attributes['aluno'])){
            $alunos = serialize($attributes['aluno']);
        }
        Validator::extend('valida_email_aluno', function($attribute, $value, $parameters, $validator) {
            $alunos  = unserialize($parameters[0]);
            foreach($alunos as $aluno){
                $user = User::find($aluno);
                if($user->email == $value){
                    return false;
                }
            }
            return true;
        });

        return  [
            'nome'      =>'required',
            'email'     =>'required|email|valida_email_aluno:'.$alunos.'|unique:users,email,'.$user_id,
        ];

    }

    public function attributes()
    {
        return[
            'nome'      =>'Nome',
            'email'     =>'E-mail',
        ];
    }

    public function messages()
    {
        return [
            'valida_email_aluno' => 'O campo e-mail não pode ser igual ao dos alunos vinculados.',
        ];
    }
}
