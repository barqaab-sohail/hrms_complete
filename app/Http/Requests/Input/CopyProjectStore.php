<?php

namespace App\Http\Requests\Input;

use Illuminate\Foundation\Http\FormRequest;

class CopyProjectStore extends FormRequest
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
            'copyTo'=> 'required|unique:input_projects,input_month_id'
        ];
    }

    public function messages()
    {
       
        return [
            'copyTo.unique' => "This Month is already entered"         
        ];
    }
}
