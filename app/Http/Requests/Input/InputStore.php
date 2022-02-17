<?php

namespace App\Http\Requests\Input;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Input\Input;
use App\Models\Input\InputProject;

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
        $sumStoredInput = Input::join('input_projects', 'inputs.input_project_id','=', 'input_projects.id')
            ->where('hr_employee_id','=',$employeeId)
            ->where('input_month_id','=',$this->input_month_id)
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
        $inputProject = InputProject::where('input_month_id', $this->month_id)->where('pr_detail_id',$this->pr_detail_id)->get();
        
        return [
            'hr_employee_id.unique_with' => "This employee input is already entered",
            'input.max'=>"This employee input exceed from 1"  ,
            'office_department_id.required'=>'Office field is requried'     
            
        ];
    }
    protected function getValidatorInstance()
    {
        $inputProject = InputProject::where('input_month_id', $this->input_month_id)->where('pr_detail_id',$this->pr_detail_id)->first();

    $data = $this->all();
    $data['input_project_id'] = $inputProject->id;
    $this->getInputSource()->replace($data);

    /*modify data before send to validator*/
    return parent::getValidatorInstance();
    }
}
