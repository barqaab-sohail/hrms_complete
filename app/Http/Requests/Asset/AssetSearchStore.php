<?php

namespace App\Http\Requests\Asset;

use Illuminate\Foundation\Http\FormRequest;

class AssetSearchStore extends FormRequest
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
        $rules = [
            'office_id' => 'required_if:as_sub_class_id,null',
            'as_sub_class_id' => 'required_if:office_id,null',

        ];

        return $rules;
    }

    public function messages()
    {

        return [
            'as_sub_class_id.required_if' => 'Sub Class is required if Office is empty',
            'office_id.required_if' => 'Office is required if Sub Class is empty',
        ];
    }
}
