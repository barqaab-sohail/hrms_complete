<?php

namespace App\Http\Requests\Project\Progress;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project\Progress\PrProgressActivity;
use App\Models\Project\Progress\PrAchievedProgress;

class AchievedProgressStore extends FormRequest
{
    public function getValidatorInstance()
    {
        $this->cleanPercentageComplete();
        return parent::getValidatorInstance();
    }

    protected function cleanPercentageComplete()
    {
        if($this->request->get('progress')){
            $this->merge([
                'progress' => floatval($this->request->get('progress'))

            ]);
        }else{
            $this->merge([
                'progress' => null
            ]);
        }

        if($this->request->get('date')){
            $this->merge([
                'date' =>  \Carbon\Carbon::parse($this->request->get('date'))->format('Y-m-d')

            ]);
        }else{
            $this->merge([
                'date' => null
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
         $activity = PrProgressActivity::find($this->activity_id);
         $lastProgress = PrAchievedProgress::where('pr_progress_activity_id',$this->activity_id)->orderBy('id', 'DESC')->first();
         
        $max = floatval($activity->weightage);

        $greaterThan = floatval(0.0);
       
        
        if($lastProgress){
            
            $greaterThan = floatval($lastProgress->percentage_complete);
            $latestDate = $lastProgress->date;
        }else{

             $latestDate="2020-1-1";
        }

       

        
         $rules = [    
            'progress' => "required|lte:$max|gt:$greaterThan",
            'date' => "required|date|after:$latestDate",

        ];

        return $rules;
    }

    // public function messages()
    // {
        
    //     $lastProgress = PrAchievedProgress::where('pr_progress_activity_id',$this->activity_id)->latest()->first();
    //     $percentage = $lastProgress->progress_complete;
         
    //     return [
    //         'progress.lte' => "$percentage",
    //     ];
    // }
}
