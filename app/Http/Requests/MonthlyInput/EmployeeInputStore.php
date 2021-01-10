<?php

namespace App\Http\Requests\MonthlyInput;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\MonthlyInput\HrMonthlyInputEmployee;

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
        $employeeId = $this->get('hr_employee_id');
        $monthId = $this->get('month_id');
        $sumInput = HrMonthlyInputEmployee::join('hr_monthly_input_projects', 'hr_monthly_input_employees.hr_monthly_input_project_id','=', 'hr_monthly_input_projects.id')
            ->where('hr_employee_id','=',$employeeId)
            ->where('hr_monthly_input_id','=',$monthId)
            ->sum('input');
        $input = $this->get('input');
        $totalInput = $input + $sumInput;
        $maxValue = 1 - $sumInput;


        $rules = [
        'hr_employee_id'=> 'required|unique_with:hr_monthly_input_employees,hr_monthly_input_project_id',
        'hr_designation_id'=> 'required',
        'input'=> "required|numeric|between:0.03,1.0|max:$maxValue",
        'remarks'=> 'required'
        
        ];

        return $rules;
    }

     public function messages()
    {
        
        return [
            'hr_employee_id.unique_with' => "This employee input is already entered"        
            
        ];
    }
}
