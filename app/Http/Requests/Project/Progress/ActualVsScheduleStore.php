<?php

namespace App\Http\Requests\Project\Progress;

use Illuminate\Foundation\Http\FormRequest;

class ActualVsScheduleStore extends FormRequest
{
    public function getValidatorInstance()
    {
        $this->cleanDate();
        return parent::getValidatorInstance();
    }

    protected function cleanDate()
    {
        if ($this->month) {
            $this->merge([
                'month' => \Carbon\Carbon::parse($this->month)->format('Y-m-d')
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
            'pr_contractor_id' => 'required',
            'month' => 'required|unique_with:pr_actual_vs_schedules,pr_contractor_id,' . $this->actual_schedule_id,
            'schedule_progress' => 'required',
            'actual_progress' => 'nullable',
            'current_month_progress' => 'nullable',

        ];

        return $rules;
    }
    public function messages()
    {

        return [
            'month.unique_with' => 'This Month already entered',

        ];
    }
}
