<?php

namespace App\Http\Requests\Project\Progress;

use Illuminate\Foundation\Http\FormRequest;

class MonthlyProgressStore extends FormRequest
{
    public function getValidatorInstance()
    {
        $this->cleanDate();
        return parent::getValidatorInstance();
    }

    protected function cleanDate()
    {
        if($this->date){
            $this->merge([
                'date' => \Carbon\Carbon::parse($this->date)->format('Y-m-d')
            ]);
        }

    }


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
            'pr_progress_activity_id' => 'required',
            'date' => 'required',
            'scheduled' => 'required|unique_with:pr_monthly_progresses,date,'.$this->monthlyProgress_id,
            'actual' => 'required|unique_with:pr_monthly_progresses,date,'.$this->monthlyProgress_id,
        ];

        return $rules;
    }

    public function messages()
    {
        
        return [
            'pr_progress_activity_id.required' => 'Activity Name is Required'. $this->date,
            'scheduled.unique_with' => 'This Activity Scheduled Progress is already Entered',
            'actual.unique_with' => 'This Activity Actual Progress is already Entered',
        ];
    }


}
