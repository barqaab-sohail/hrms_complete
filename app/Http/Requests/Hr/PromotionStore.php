<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class PromotionStore extends FormRequest
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
           
            'document'=>'required|file|max:2000|mimes:pdf' ,
            'hr_designation_id' => 'required',
            'effective_date' => 'required',
            'hr_salary_id' => 'required',
            'hr_manager_id' => 'required',
            'hr_department_id' => 'required',
            'category' => 'required',
            'remarks' => 'required|max:100',

        ];
    }
}
