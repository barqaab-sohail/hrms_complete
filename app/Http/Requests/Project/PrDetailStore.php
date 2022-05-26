<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class PrDetailStore extends FormRequest
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
            'name'=>'required|max:510|unique:pr_details,name,'.session('pr_detail_id'),
            'client_id'=>'required|numeric',
            'commencement_date'=>'required|date',
            'contractual_completion_date'=>'nullable|date|after:commencement_date',
            'actual_completion_date'=>'nullable|date', 
            'pr_status_id'=>'required|numeric',
            'pr_role_id'=>'required|numeric',
            'contract_type_id'=>'required|numeric',
            'project_no'=>'required|max:4|unique:pr_details,project_no,'.session('pr_detail_id'),
            'share'=>'required',
        ];
    }
}
