<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'item_category' => 'required|not_in:0',
            'item_name'     => 'required|min:2',
            'item_type'     => 'required|in:0,1',
            'item_image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'item_price'    => 'required_if:item_type,==,1|nullable|numeric',
        ];
    }

    public function messages()
    {
        return[
            'item_category.required' => 'The category field is required.',
            'item_name.required'     => 'The food name field is required.',
            'item_image.required'    => 'The food image field is required.',
            'item_price.required_if' => 'The food price field is required.',
        ];
    }
}
