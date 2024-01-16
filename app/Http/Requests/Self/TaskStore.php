<?php

namespace App\Http\Requests\Self;

use Illuminate\Foundation\Http\FormRequest;

class TaskStore extends FormRequest
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
        
        
        'task_detail'=> 'required|max:190',
        'completion_date'=> 'required|date',
        'target_completion'=> 'required|date|before:completion_date',
        
        ];

    
        return $rules;
    }
}
