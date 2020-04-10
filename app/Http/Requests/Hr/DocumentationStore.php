<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class DocumentationStore extends FormRequest
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
             'document'=>'file|max:2000|mimes:pdf,jpg,jpeg,tif,png'
        ];
    }

      public function messages()
    {
        return [
            'document.mimes' => 'only pdf, jpg, tif and png attachment allowed',
        ];
    }
}
