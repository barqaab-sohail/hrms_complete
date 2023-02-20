<?php

namespace App\Http\Requests\Project\Progress;

use Illuminate\Foundation\Http\FormRequest;

class DelayReasonStore extends FormRequest
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
            'pr_contractor_id' => 'required',
            'reason' => "required|max:20000",
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'pr_contractor_id.required' => 'Contract name is requried',

        ];
    }
}
