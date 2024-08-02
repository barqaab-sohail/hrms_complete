<?php

namespace App\Http\Requests\Cv;

use Illuminate\Foundation\Http\FormRequest;

class CvDetailStore extends FormRequest
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
         $today = \Carbon\Carbon::today();

         return [
            
            'full_name' => 'required|max:190',
            'cnic' => 'nullable|min:15|max:15|unique:cv_details,cnic,'.session('cv_detail_id'),
            'date_of_birth' => 'nullable|date|before:18 years ago|after:75 years ago',
            'job_starting_date' => 'required|date',
            'address' => 'nullable|max:191',
            'city' => 'nullable|max:191',
            'province' => 'nullable|max:191',
            'country_id' => 'required|max:191',
            'email'=>'nullable|max:190|email|unique:cv_contacts,email',
            'phone.*' => 'required|max:15|distinct',
            'degree_name.*' => 'required|distinct',
            'institute.*' => 'nullable|max:191',
            'passing_year.*' => 'nullable|distinct',
            'speciality_name.*' => 'required',
            'discipline_name.*' => 'required',
            'stage_name.*' => 'required',
            'year.*' => 'required',
            'foreign_experience'=>'nullable|numeric',
            'donor_experience'=>'nullable|numeric',
            'membership_name.*'=>'nullable|distinct',
            'barqaab_employment' => 'required',
             'skill.*' => 'nullable|max:190',
            'ref_detail'=> 'nullable|max:190',
            'cv_submission_date'=>'nullable|date|before_or_equal:'.$today,
            'cv' => 'required|file|max:4000|mimes:doc,docx,pdf',

        ];
    }

    public function messages()
    {
       
        return [
            'date_of_birth.before' => 'Date of Birth required minimum 18 years of Age',
             'date_of_birth.after' => 'More than 75 Years of Age is not allowed',
            'job_starting_date.after' => 'Job Startingg Date after 18 years of Age',
            'phone.*.distinct' => 'Two Phone Numbers must be different',
            'degree_name.*.distinct' => 'Two Degree Names must be different',
            'passing_year.*.distinct' => 'Two Degrees on same year is not allowed',
            'membership_number.*.required_if' => 'Pakistan Engineering Council Number is required',
            'cv_submission_date.before_or_equal'=>'The cv submission date must be a date before or equal today',
            'cv.required'=>"CV Attachment is Required",
            'year.*.required' => 'years of experience is required',
            'phone.*.required' => 'mobil number is required',

        ];
    }
}
