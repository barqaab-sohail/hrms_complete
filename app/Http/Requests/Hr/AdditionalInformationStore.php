<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class AdditionalInformationStore extends FormRequest
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
            'licence_no' => 'required_with:licence_expiry',
            'licence_expiry' => 'required_with:licence_no',
            'passport_no' => 'required_with:passport_expiry',
            'passport_expiry' => 'required_with:passport_no',
            'membership_no' => 'required_with:membership_id'
        ];

       return $rules;
    }
}
