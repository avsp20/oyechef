<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'name' => 'required|regex:/^[a-zA-Z]+$/u',
            'lname' => 'required|regex:/^[a-zA-Z]+$/',
            'phone' => 'required',
            'address' => 'nullable|max:200',
            'zipcode' => 'nullable|regex:/^[\s\w-]*$/',
        ];
    }

    public function messages()
    {
        return[
            'name.required' => 'The first name field is required.',
            'lname.required' => 'The last name field is required.',
        ];
    }
}
