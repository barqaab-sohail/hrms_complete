<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class OfficeStore extends FormRequest
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
            'name'=> 'required|unique:offices,name,'.$this->office_id,
            'country_id'=>'nullable',
            'state_id'=>'nullable',
            'city_id'=>'required_with:country_id,state_id,address',
            'is_active'=> 'required',
           
        ];

    return $rules;
    }
}
