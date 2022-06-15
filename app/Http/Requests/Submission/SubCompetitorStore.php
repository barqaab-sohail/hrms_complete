<?php

namespace App\Http\Requests\Submission;

use Illuminate\Foundation\Http\FormRequest;

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
        $rules = [
            'name'=> 'required',
            


        ];

        if($this->multi_currency){
            $rules += [ 'currency_id.*'=>'required_with:conversion_rate,currency_price',
                        'conversion_rate.*'=>'required_with:currency_id,currency_price',
                        'currency_price.*'=>'required_with:currency_id,conversion_rate',
                        ];
        }

    return $rules;
    }
}
