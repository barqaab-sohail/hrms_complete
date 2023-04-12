<?php

namespace App\Http\Requests\Project\Progress;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project\Progress\PrProgressActivity;
use App\Models\Project\PrDetail;
use App\Models\Project\Progress\PrSubTotalWeightage;

class ActivityStore extends FormRequest
{
    private $total;
    private $PrSubProjectWeightageSum;

    public function getValidatorInstance()
    {
        $this->cleanWeightage();
        return parent::getValidatorInstance();
    }

    protected function cleanWeightage()
    {
        if ($this->request->has('weightage')) {
            $this->merge([
                'weightage' => floatval($this->request->get('weightage'))
            ]);
        } else {
            $this->merge([
                'weightage' => null
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
        $sum = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->sum('weightage');
        $PrProgressActivityId = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->pluck('id')->toArray();
        $this->PrSubProjectWeightageSum = PrSubTotalWeightage::whereIn('pr_progress_activity_id',  $PrProgressActivityId)->sum('total_weightage');

        $project = PrDetail::find(session('pr_detail_id'));
        if ($project->prSubProject->count()) {
            $this->total = $project->prSubProject->count() * 100;
        } else {
            $this->total = 100;
        }

        $max = $this->total - $sum;

        $maxSubProjectWeightage = 100 - $this->PrSubProjectWeightageSum;


        $rules = [
            'name' => 'required',

        ];

        //If method is POST then document is required otherwise in Patch method document is nullable.
        if ($this->activity_id) {
            $sum = PrProgressActivity::where('id', $this->activity_id)->first();
            $max = $max + $sum->weightage;
            $sumSubProject = PrSubTotalWeightage::where('pr_progress_activity_id', $this->activity_id)->first();
            $maxSubProjectWeightage = $maxSubProjectWeightage + $sumSubProject->total_weightage;

            $rules += ['weightage' => "required|lte:$max", 'total_weightage' => "nullable|lte:$maxSubProjectWeightage"];
        } else {
            $rules += ['weightage' => "required|lte:$max", 'total_weightage' => "nullable|lte:$maxSubProjectWeightage"];
        }

        return $rules;
    }

    public function messages()
    {

        return [
            'weightage.lte' => "Total Weightage of all activities less than or equal to $this->total",
            'total_weightage.lte' => "Total Weightage of Sub Projects less than or equal to 100",
        ];
    }
}
