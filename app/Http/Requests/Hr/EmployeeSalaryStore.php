<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeSalaryStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'hr_employee_id' => 'required',
            'effective_date'=>'required|date',
            'hr_salary' => 'required',
            'hr_allowance_name_id.*' => 'distinct',
        ];

        if (request()->has('hr_allowance_name_id.1')) {
            $rules += ['hr_allowance_name_id.1'=>'required'];
        }

        if (request()->has('hr_allowance_name_id.2')) {
            $rules += ['hr_allowance_name_id.2'=>'required'];
        }

        if (request()->has('hr_allowance_name_id.3')) {
            $rules += ['hr_allowance_name_id.3'=>'required'];
        }
        if (request()->has('hr_allowance_name_id.4')) {
            $rules += ['hr_allowance_name_id.4'=>'required'];
        }
        
        return $rules;
    }
}