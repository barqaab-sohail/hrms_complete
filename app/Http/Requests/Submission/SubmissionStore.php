<?php

namespace App\Http\Requests\Submission;

use Illuminate\Foundation\Http\FormRequest;

class SubmissionStore extends FormRequest
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
        
       // $totalWeightage = $this->technical_weightage + $this->financial_weightage;

        $rules = [
            'sub_division_id'=> 'required',
            'sub_type_id'=> 'required',
            'project_name'=> 'required|max:510',
            'client_id'=> 'required',
            'submission_no'=> 'required',
        ];

        //If method is POST then document is required otherwise in Patch method document is nullable.
            if ($this->getMethod() == 'PATCH') {
                $rules += [ 'passing_marks'=>'nullable|numeric|max:1000',
                            'technical_weightage'=>'nullable|numeric|max:100',
                            'financial_weightage'=>'nullable|numeric|max:100|required_unless:technical_weightage,null',

                            ];
            }

    return $rules;
    }

}
