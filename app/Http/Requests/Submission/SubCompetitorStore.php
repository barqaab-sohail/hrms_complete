<?php

namespace App\Http\Requests\Submission;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Submission\Submission;

class SubCompetitorStore extends FormRequest
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
        $submission = Submission::find(session('submission_id'));
        $maxTechnicalNumber = $submission->subDescription->total_marks;

        $rules = [
            'name'=> 'required',
            'technical_number'=>"nullable|numeric|max:$maxTechnicalNumber",
            

        ];

        if($this->multi_currency){
            $rules += [ 'total_price'=>'required',
                        'currency_id.*'=>'required_with:conversion_rate,currency_price',
                        'conversion_rate.*'=>'required_with:currency_id,currency_price',
                        'currency_price.*'=>'required_with:currency_id,conversion_rate',
                        ];
        }

    return $rules;
    }
}
