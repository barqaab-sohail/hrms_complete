<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeStore extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'father_name' => 'required_if:husband_name,null',
            'husband_name' => 'required_if:father_name,null',
            'cnic' => 'required|min:15|max:15|unique:hr_employees,cnic,' . session('hr_employee_id'),
            'date_of_birth' => 'required|date|before:18 years ago',
            'cnic_expiry' => 'required|date',
            'gender_id' => 'required',
            'marital_status_id' => 'required',
            'religion_id' => 'required',
            'employee_no' => 'digits:7|unique:hr_employees,employee_no,' . session('hr_employee_id'),
        ];
    }

    public function messages()
    {
        return [
            'father_name.required_if' => 'Father Name Or Husband Name required',
            'husband_name.required_if' => 'Father Name Or Husband Name required',
            'marital_status_id.required' => 'marital status field is required',
            'gender_id.required' => 'geneder field is required',
            'religion_id.required' => 'religion  field is required',
            'date_of_birth.before' => 'Age must be 18 years old',
            'cnic_expiry.required' => 'CNIC expiry date is rquired',


        ];
    }
}
