<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class HrMonthlyReportProjectStore extends FormRequest
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
        'hr_monthly_report_id'=> 'required',
        'pr_detail_id'=> 'required|unique_with:hr_monthly_report_projects,hr_monthly_report_id'
        //'mobile' => 'required|unique:users,mobile,NULL,id,isd,' . $request->isd,
        ];

         return $rules;
    }

    public function messages()
    {
        
        return [
            'pr_detail_id.unique_with' => 'Project already entered',
        ];
    }
}
