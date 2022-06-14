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
            $rules += [ 'currency_id.*'=>'nullable|required_with:conversion_rate',
            'conversion_rate.*'=>'nullable|required_with:currency_id'];
        }

    return $rules;
    }
}
