<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class NewsFeedRequest extends FormRequest
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
            'content' => 'required',
            'file'    => 'nullable|mimes:jpeg,png,bmp,tiff,mp4,ogx,oga,ogv,ogg,webm|max:5120',
        ];
    }

    public function messages()
    {
        return[
            'file.required' => "Please upload video or image on your feed."
        ];
    }
}
