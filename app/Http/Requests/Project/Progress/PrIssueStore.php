<?php

namespace App\Http\Requests\Project\Progress;

use Illuminate\Foundation\Http\FormRequest;

class PrIssueStore extends FormRequest
{
    
    public function getValidatorInstance()
    {
        $this->cleanDate();
        return parent::getValidatorInstance();
    }

    protected function cleanDate()
    {
        if($this->resolve_date){
            $this->merge([
                'resolve_date' => \Carbon\Carbon::parse($this->resolve_date)->format('Y-m-d')
            ]);
        }

    }


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
            'description' => "required",
            'responsibility' => "required",
            'status' => "required",
            'resolve_date' => "required_if:status,Resolved",
        ];

        return $rules;
    }
}
