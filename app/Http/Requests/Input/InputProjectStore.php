<?php

namespace App\Http\Requests\Input;

use Illuminate\Foundation\Http\FormRequest;

class InputProjectStore extends FormRequest
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
        'input_month_id'=> 'required',
        'pr_detail_id'=> 'required|unique_with:input_projects,input_month_id'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'pr_detail_id.unique_with' => 'Project already entered',
        ];
    }
}
