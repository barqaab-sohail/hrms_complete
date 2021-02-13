<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentStore extends FormRequest
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

            'reference_no'=>'required|max:190',
            'joining_date'=>'required|date',
            'expiry_date'=>'nullable|date|after:joining_date',
            'employee_designation_id'=>'required|numeric',
            'employee_manager_id'=>'required|numeric',
            'employee_department_id'=>'required|numeric',
            'employee_category_id'=>'required',
            'employee_salary_id'=>'required|numeric',
            'employee_grade_id'=>'nullable',
            'hr_employee_type_id'=>'required',
            'hr_letter_type_id'=>'required|numeric',
            'employee_project_id'=>'required|numeric',
            'remarks'=>'nullable|max:190',   
        ];
    }
}
