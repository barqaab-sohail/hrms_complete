<?php

namespace App\Http\Requests\Input;

use Illuminate\Foundation\Http\FormRequest;

class InputMonthStore extends FormRequest
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
        $id = request()->input('month_id');
        $rules = [
        'month'=> 'required',
        'year'=> 'required|unique_with:input_months,month,'.$id,
        'is_lock'=> 'required',
        ];

        return $rules;
    }

    public function messages()
    {
       
        return [
            'year.unique_with' => "This Month & Year is already entered"        
            
        ];
    }
}
