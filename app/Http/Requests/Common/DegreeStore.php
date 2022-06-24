<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class DegreeStore extends FormRequest
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
            'degree_name'=> 'required|unique:educations,degree_name,'.$this->degree_id,
            'level'=> 'required',
           
        ];

    return $rules;
    }
}
