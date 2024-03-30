<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class PrPartnerStore extends FormRequest
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
              
            'partner_id' => 'required|unique:pr_partners,partner_id,'.$this->pr_partner_id,
            'pr_role_id' => 'required',
            'share' => 'required|max:50',
            'authorize_person' => 'nullable|max:50',
            'designation' => 'nullable|max:50',
            'phone' => 'nullable|max:15',
            'mobile' => 'nullable|max:15',
            'email' => 'nullable|max:50',
           
        ];

        return $rules;
    }

    public function messages()
    {
        
        return [
            'partner_id.unique' => 'This Partner is already entered',
            
        ];
    }
}
