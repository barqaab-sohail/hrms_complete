<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class ExperienceStore extends FormRequest
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
        
        
        'organization'=> 'required|max:90',
        'job_title'=> 'required|max:70',
        'from'=> 'required|max:4',
        'to'=> 'required|max:4',
        'country_id'=> 'nullable|max:4',
        'activities'=> 'nullable|max:65535',
        
        ];

    
        return $rules;
      
    }
}
