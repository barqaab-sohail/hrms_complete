<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class PrPositionStore extends FormRequest
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
            'hr_designation_id'=>'required',
            'pr_position_type_id'=>'required|numeric',
            'total_mm'=>'nullable|numeric|min:0.001|max:6000',
        ];
    }
}
