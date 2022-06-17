<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class MenuCategoryRequest extends FormRequest
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
        if(request()->route()->getName() == "admin.add-category"){
            $category = 'required|min:2|unique:menu_categories,name,NULL,id,deleted_at,NULL';
        }elseif(request()->route()->getName() == "admin.update-category"){
            $category = 'required|min:2|unique:menu_categories,name,' . $this->request->get('cat_id') . ',id,deleted_at,NULL';
        }
        return [
            'category_name' => $category
        ];
    }

    public function messages()
    {
        return[
            'category_name.required' => 'The category name field is required.',
        ];
    }
}
