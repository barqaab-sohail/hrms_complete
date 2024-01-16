<?php

namespace App\Http\Requests\Project\Progress;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project\Progress\PrActualVsSchedule;
use Illuminate\Validation\Rule;

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
        $month = "2020-01-01";
        $maxSchduleProgress = 0;
        $maxActualProgress = 0;
        $maxCurrentMonthProgress = 0;

        if ($this->month) {
            //check it is edit request or store request
            if ($this->actual_schedule_id) {
                $lastScheduleProgress = PrActualVsSchedule::where('pr_detail_id', session('pr_detail_id'))->where('pr_contractor_id', $this->pr_contractor_id)->where('id', '!=', $this->actual_schedule_id)->where('month', '<', $this->month)->orderBy('id', 'DESC')->first();
            } else {
                $lastScheduleProgress = PrActualVsSchedule::where('pr_detail_id', session('pr_detail_id'))->where('pr_contractor_id', $this->pr_contractor_id)->where('month', '<', $this->month)->orderBy('id', 'DESC')->first();
            }

            $totalCurrentMonthProgress = PrActualVsSchedule::where('pr_detail_id', session('pr_detail_id'))->where('pr_contractor_id', $this->pr_contractor_id)->sum('current_month_progress') + $this->current_month_progress;
            $maxCurrentMonthProgress = 100 - $totalCurrentMonthProgress;

            if ($lastScheduleProgress) {
                $maxSchduleProgress = $lastScheduleProgress->schedule_progress;
                $month =  $lastScheduleProgress->month;
                if ($lastScheduleProgress->actual_progress) {
                    $maxActualProgress = $lastScheduleProgress->actual_progress;
                }
            }
        }

        $rules = [
            'pr_contractor_id' => 'required',
            'month' => ['required', 'date', "after:$month", Rule::unique('pr_actual_vs_schedules')->where(fn ($query) => $query->where('pr_contractor_id', request()->pr_contractor_id)->where('id', '!=', $this->actual_schedule_id))],
            'schedule_progress' => "required|gte:$maxSchduleProgress|lte:100",
            'actual_progress' => "nullable|gte:$maxActualProgress|lte:100",
            'current_month_progress' => "nullable|lte:$maxCurrentMonthProgress",

        ];

        return $rules;
    }
    public function messages()
    {

        return [
            'pr_contractor_id.required' => 'Contract name is requried',
            'month.unique' => 'This Month already entered',
            'current_month_progress.lte' => 'Accumulative total of current month progress cannot be greater than 100'

        ];
    }
}
