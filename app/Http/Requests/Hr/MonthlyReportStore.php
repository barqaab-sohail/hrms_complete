<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class MonthlyReportStore extends FormRequest
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
        'month'=> 'required|max:90',
        'year'=> 'required|max:4|unique_with:hr_monthly_reports,month'
        //'mobile' => 'required|unique:users,mobile,NULL,id,isd,' . $request->isd,
        ];

    
        return $rules;
    }

    public function messages()
    {
        
        return [
            'year.unique_with' => 'month already entered',
        ];
    }

}
