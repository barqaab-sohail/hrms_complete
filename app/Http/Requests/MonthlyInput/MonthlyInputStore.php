<?php

namespace App\Http\Requests\MonthlyInput;

use Illuminate\Foundation\Http\FormRequest;

class MonthlyInputStore extends FormRequest
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
        'hr_monthly_input_id'=> 'required',
        'pr_detail_id'=> 'required|unique_with:hr_monthly_input_projects,hr_monthly_input_id'
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
