<?php

namespace App\Http\Requests\MonthlyInput;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeInputStore extends FormRequest
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
        'hr_employee_id'=> 'required|unique:hr_monthly_input_employees,hr_employee_id',
        'hr_designation_id'=> 'required',
        'input'=> 'required|numeric|between:0.03,1.0',
        'remarks'=> 'required'
        
        ];

        return $rules;
    }

     public function messages()
    {
        return [
            'hr_employee_id.unique' => 'This employee input is already entered'         
            
        ];
    }
}
