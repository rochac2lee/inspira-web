<?php
namespace App\Http\Requests\Fr;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\User;
use App\UserPermissao;

class ProfessorRequest extends FormRequest
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
        Validator::extend('valida_email_na_escola', function($attribute, $value, $parameters, $validator) {

            $escolaId = $parameters[0];
            $permissao = $parameters[1];

            $usuario = User::where('email',$value)
                            ->first();
            if($usuario != null)
            {
                $usuarioEscola = User::where('escola_id',$escolaId)
                                        ->where('permissao',$permissao)
                                        ->find($usuario->id);

                $usuarioEscolaPermissao = UserPermissao::where('escola_id',$escolaId)
                                                ->where('permissao',$permissao)
                                                ->where('user_permissao.user_id',$usuario->id)
                                                ->first();

                if($usuarioEscola == null && $usuarioEscolaPermissao == null)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return true;
            }

        });

        $attributes = parent::all();
//|valida_email_na_escola:'.$this->input('escola_id').','.$this->input('permissao'),
        $validacao = [
            'name'            =>'required',
            'nome_completo'   =>'required',
            'email'           =>'required|email',
            'password'        =>'required',
            'permissao'       =>'required',
            'escola_id'       =>'required',
        ];

        return $validacao;
    }

    public function attributes()
    {
        return[
            'name'          =>'Nome',
            'nome_completo' =>'Nome completo',
            'password'      =>'senha',
            'email'         =>'E-mail',
            'permissao'     =>'Permissão',
            'escola_id'     =>'Código da escola',
        ];
    }

    public function messages()
    {
        return [
            'valida_email_na_escola'  => 'Já existe esse e-mail alocado para essa escola',
        ];
    }
}
