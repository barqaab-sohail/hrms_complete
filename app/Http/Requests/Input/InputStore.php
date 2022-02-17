<?php

namespace App\Http\Requests\Input;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Input\Input;

class InputStore extends FormRequest
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
        $sumStoredInput = Input::join('input_projects', 'inputs.input_project_id','=', 'input_projects.id')
            ->where('hr_employee_id','=',$employeeId)
            ->where('input_month_id','=',$monthId)
            ->sum('input');
        
      
        $maxValue = 1 - $sumStoredInput;

        //check edit request
        if ($this->input_id) {
            $input = Input::find($this->input_id);
            $maxValue = 1 - $sumStoredInput + $input->input ;
        }
        


        $rules = [
        'hr_employee_id'=> 'required|unique_with:inputs,input_project_id,'.$this->input_id,
        'pr_detail_id'=> 'required',
        'input_month_id'=> 'required',
        'input'=> "required|numeric|between:0.03,1.0|max:$maxValue"
        
        ];

        if($this->pr_detail_id ==1) {
            $rules += ['office_department_id'=> "required"];
        }else{
             $rules += ['hr_designation_id'=> "required"];
        }

        return $rules;
    }

    public function messages()
    {
       
        return [
            'hr_employee_id.unique_with' => "This employee input is already entered, $this->input_project_id",
            'input.max'=>"This employee input exceed from 1"  ,
            'office_department_id.required'=>'Office field is requried'     
            
        ];
    }
}
