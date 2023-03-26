<?php

namespace App\Http\Requests\Project\Rights;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRightsStore extends FormRequest
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
            'hr_employee_id' => ['required', Rule::unique('pr_rights')->where(fn ($query) => $query->where('pr_detail_id', request()->pr_detail_id)->where('id', '!=', $this->right_id))],
            'pr_detail_id' => 'required',
            'progress' => 'required_without:invoice',
            'invoice' => 'required_without:progress',
        ];

        return $rules;
    }


    public function messages()
    {

        return [
            'hr_employee_id.unique' => employeeFullName($this->hr_employee_id) . '  Rights agasint this Project have already been assigned',
        ];
    }
}
