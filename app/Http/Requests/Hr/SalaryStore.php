<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class SalaryStore extends FormRequest
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
            'total_salary'=>'required|min:5|max:7',
        ];
    }

     public function messages()
    {
        return [
            'total_salary.min' => 'salary minimum 4 digits',
            'total_salary.max' => 'salary maximumt 6 digits',
             
            
        ];
    }
}
