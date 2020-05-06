<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class EducationStore extends FormRequest
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
            'education_id'=> 'required',
            'institute'=> 'nullable|max:190',
            'major'=> 'nullable|max:190',
            'from'=> 'nullable|max:4',
            'to'=> 'required|max:4',
            'total_marks'=> 'nullable|max:4',
            'marks_obtain'=> 'nullable|max:4|lt:total_marks',
            'grade'=> 'nullable|max:10',
            'country_id'=> 'required',
        ];
    }


    public function messages(){

        return [
            'marks_obtain.lt'=> 'The marks obtain must be less than total marks',


            ];

    }
}
