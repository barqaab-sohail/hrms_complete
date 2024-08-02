<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PrMonthlyExpenseStore extends FormRequest
{

    public function __construct(\Illuminate\Http\Request $request)
    {
        $request->request->add(['pr_detail_id' => session('pr_detail_id')]);
    }

    public function getValidatorInstance()
    {
        $this->cleanMonth();
        return parent::getValidatorInstance();
    }

    protected function cleanMonth()
    {
        if ($this->month) {
            $this->merge([
                'month' => \Carbon\Carbon::parse($this->month)->format('Y-m-d')
            ]);
        }
    }

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

            'pr_detail_id' => 'required',
            'month' => ['required', Rule::unique('pr_monthly_expenses')->where(fn ($query) => $query->where('pr_detail_id', request()->pr_detail_id)->where('id', '!=', $this->expense_id))],
            'salary_expense' => 'required_without:non_salary_expense',
            'non_salary_expense' => 'required_without:salary_expense',

        ];

        return $rules;
    }

    public function messages()
    {

        return [
            'month.unique' => 'This Month Expenses already entered',

        ];
    }
}
