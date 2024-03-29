<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class DesignationStore extends FormRequest
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
            'name'=> 'required|unique:hr_designations,name,'.$this->designation_id,
            'level'=> 'required',
           
        ];

    return $rules;
    }
}
