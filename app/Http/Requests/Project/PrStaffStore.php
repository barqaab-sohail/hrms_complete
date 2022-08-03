<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class PrStaffStore extends FormRequest
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
              
            'hr_employee_id' => 'required',
            'position'=>'required',
            'from'=>'required',
            'status'=>'required',
            'to'=>'required_if:status,Input Ended',
    
        ];

        return $rules;
    }
}
