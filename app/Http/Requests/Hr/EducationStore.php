<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use DB;

class EducationStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

      public function __construct(\Illuminate\Http\Request $request)
    {
        Validator::extend('unique_education', function ($attribute, $value, $parameters, $validator) {
                $count = DB::table('hr_educations')->where('education_id', $value)
                                    ->where('hr_employee_id', $parameters[0])
                                    ->count();

            return $count === 0;
        });


    }


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
            
            
            'institute'=> 'nullable|max:190',
            'major'=> 'nullable|max:190',
            'from'=> 'nullable|max:4',
            'to'=> 'required|max:4',
            'total_marks'=> 'nullable|max:4',
            'marks_obtain'=> 'nullable|max:4|lt:total_marks',
            'grade'=> 'nullable|max:10',
            'country_id'=> 'required',
        ];

        //If POST Method then run this code otherwise in Patch Method dupblicate checck in uptdate function in controller.
        if ($this->getMethod() == 'POST') {
        $rules += ['education_id'=> "required|unique_education:".session('hr_employee_id')];
        }

    return $rules;

    }


    public function messages(){

        return [
            'marks_obtain.lt'=> 'The marks obtain must be less than total marks',
            'education_id.unique_education'=> 'This degree is already entered'
            ];

    }
}
