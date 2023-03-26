<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'month' => 'required|max:90',
            'year' => ['required', 'max:4', Rule::unique('hr_monthly_reports')->where(fn ($query) => $query->where('month', request()->month)->where('id', '!=', $this->id))],
            //'mobile' => 'required|unique:users,mobile,NULL,id,isd,' . $request->isd,
        ];


        return $rules;
    }

    public function messages()
    {

        return [
            'year.unique' => 'month already entered',
        ];
    }
}
