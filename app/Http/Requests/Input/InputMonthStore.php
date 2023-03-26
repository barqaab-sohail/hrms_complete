<?php

namespace App\Http\Requests\Input;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'month' => 'required',
            'year' => ['required', Rule::unique('input_months')->where(fn ($query) => $query->where('month', request()->month)->where('id', '!=', $this->id))],
            'is_lock' => 'required',
        ];

        return $rules;
    }

    public function messages()
    {

        return [
            'year.unique' => "This Month & Year is already entered"
        ];
    }
}
