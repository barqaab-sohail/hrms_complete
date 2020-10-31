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
            'project_name'=> 'required|max:190',
            'client_id'=> 'required',
            'submission_date'=> 'required',
            'submission_time'=> 'required',
            'address'=> 'required|max:190',
            'designation'=> 'required|max:190',

        ];

        //If POST Method then run this code otherwise in Patch Method dupblicate checck in uptdate function in controller.
        // if ($this->getMethod() == 'POST') {
        // $rules += ['education_id'=> "required|unique_education:".session('hr_employee_id')];
        // }

    return $rules;
    }

}
