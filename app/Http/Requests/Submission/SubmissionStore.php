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
        $rules = [
            'sub_division_id'=> 'required',
            'sub_type_id'=> 'required',
            'project_name'=> 'required|max:250',
            'client_id'=> 'required',
            'submission_no'=> 'required',
        ];

    return $rules;
    }

}
