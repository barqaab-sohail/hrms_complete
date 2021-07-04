<?php

namespace App\Http\Requests\Asset;

use Illuminate\Foundation\Http\FormRequest;

class AsLocationStore extends FormRequest
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
     
        'office_id'=> 'required_without:hr_employee_id',
        'hr_employee_id'=> 'required_without:office_id',
        'date'=> 'required',
        ];

        return $rules;
    }
}
