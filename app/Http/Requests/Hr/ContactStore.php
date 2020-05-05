<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class ContactStore extends FormRequest
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
        return [
            'hr_contact_type_id'=> 'required',
            'house'=> 'nullable|max:190',
            'street'=> 'nullable|max:190',
            'town'=> 'required|max:190',
            'tehsil'=> 'nullable|max:190',
            'city_id'=> 'required|max:5',
            'state_id'=> 'required|max:4',
            'country_id'=> 'required|max:3',
            'mobile'=> 'required|max:15',
            'landline'=> 'nullable|max:15',
            'email'=> 'nullable|email|max:50',

        ];
    }
}
