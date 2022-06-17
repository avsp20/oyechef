<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'fname'     => 'required|min:2|max:100',
            'lname'     => 'required|min:2|max:100',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'cpassword' => 'required|min:6|required_with:password|same:password',
        ];
    }

    public function messages()
    {
        return[
            'fname.required' => 'The first name field is required.',
            'lname.required' => 'The last name field is required.',
        ];
    }
}
