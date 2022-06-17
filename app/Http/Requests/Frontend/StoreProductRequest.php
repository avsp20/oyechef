<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'category'   => 'required|not_in:0',
            'food_name'  => 'required|min:2',
            'price_type' => 'required|in:0,1',
            'food_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'food_price' => 'required_if:price_type,==,1|nullable|numeric'
        ];
    }

    public function messages()
    {
        return[
            'food_price.required_if' => 'The food price field is required.'
        ];
    }
}
