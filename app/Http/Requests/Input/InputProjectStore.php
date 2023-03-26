<?php

namespace App\Http\Requests\Input;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InputProjectStore extends FormRequest
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
            'input_month_id' => 'required',
            'pr_detail_id' => ['required', Rule::unique('input_projects')->where(fn ($query) => $query->where('input_month_id', request()->input_month_id)->where('id', '!=', $this->id))],
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'pr_detail_id.unique' => 'Project already entered',
        ];
    }
}
