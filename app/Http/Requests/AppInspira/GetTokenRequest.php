<?php
namespace App\Http\Requests\AppInspira;

use Validator;
use Illuminate\Foundation\Http\FormRequest;

class GetTokenRequest extends FormRequest
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
        return [
            'email'=>'required|email',
            'password'=>'required|min:6',
        ];
    }

    public function attributes()
    {
        return[
            'email'=>'E-mail',
            'password'=>'password',
        ];
    }
}
