<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class FrontUserRequest extends FormRequest
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
            'fname'    => 'required|min:2|max:100',
            'lname'    => 'required|min:2|max:100',
            'username' => 'nullable|min:2|string|regex:/\A(?!.*[:;]-\))[ -~]+\z/|alpha_dash|required_if:is_username_active,on',
            'email'    => 'required|email|unique:users,email,' . Auth::guard('users')->user()->id . '',
            'phone'    => 'required',
            'address'  => 'required',
        ];
    }

    public function messages()
    {
        return[
            'fname.required'       => 'The first name field is required.',
            'lname.required'       => 'The last name field is required.',
            'username.required_if' => 'The username field is required if you want to use username instead of name.'
        ];
    }
}
