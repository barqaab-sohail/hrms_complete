<?php

namespace App\Http\Requests\Project\Progress;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project\Progress\PrProgressActivity;

class ActivityStore extends FormRequest
{
    
    public function getValidatorInstance()
    {
        $this->cleanWeightage();
        return parent::getValidatorInstance();
    }

    protected function cleanWeightage()
    {
        if($this->request->has('weightage')){
            $this->merge([
                'weightage' => floatval($this->request->get('weightage'))
            ]);
        }else{
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
         $sum = PrProgressActivity::where('pr_detail_id',session('pr_detail_id'))->sum('weightage');
         
         $max = 100 - $sum;

         $rules = [    
            'name' => 'required',

        ];

      //If method is POST then document is required otherwise in Patch method document is nullable.
        if ($this->activity_id) {
             $sum = PrProgressActivity::where('id',$this->activity_id)->first();
            $max = $max + $sum->weightage;

            $rules += [ 'weightage' => "required|lte:$max"];
        }else{    
            $rules += [ 'weightage' => "required|lte:$max"];  
        }

        return $rules;
    }

    public function messages()
    {
       
        return [
            'weightage.lte' => 'Total Weightage of all activities less than or equal to 100 ',
        ];
    }

}
