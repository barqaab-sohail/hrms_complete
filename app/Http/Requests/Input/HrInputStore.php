<?php

namespace App\Http\Requests\Input;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Input\HrInput;

class HrInputStore extends FormRequest
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
        $sumInput = HrInput::join('hr_input_projects', 'hr_inputs.hr_input_project_id','=', 'hr_input_projects.id')
            ->where('hr_employee_id','=',$employeeId)
            ->where('hr_input_month_id','=',$monthId)
            ->sum('input');
        $input = $this->get('input');
        $totalInput = $input + $sumInput;
        $maxValue = 1 - $sumInput;


        $rules = [
        'hr_employee_id'=> 'required|unique_with:hr_inputs,hr_input_project_id',
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
