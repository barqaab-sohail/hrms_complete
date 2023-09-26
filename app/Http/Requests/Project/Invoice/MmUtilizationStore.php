<?php

namespace App\Http\Requests\Project\Invoice;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Project\Invoice\Invoice;

class MmUtilizationStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function getValidatorInstance()
    {
        $this->cleanDate();
        return parent::getValidatorInstance();
    }

    protected function cleanDate()
    {

        if ($this->month_year) {
            $this->merge([
                'month_year' => \Carbon\Carbon::parse($this->month_year)->format('Y-m-d')
            ]);
        }
        if ($this->billing_rate) {
            $this->merge([
                'billing_rate' => (int)str_replace(',', '', $this->billing_rate)
            ]);
        }
    }

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
        $invoiceMonth = '2001-01-01';
        if ($this->invoice_id) {
            $invoice = Invoice::find($this->invoice_id);
            $invoiceMonth = \Carbon\Carbon::parse($invoice->invoiceMonth->invoice_month)->format('Y-m-d');
        }
        if (!$this->double_charging) {
            $rules = [

                'hr_employee_id' => ['required'],
                'pr_position_id' => ['required'],
                'invoice_id' => ['required'],
                'month_year' => ['required', 'date'],
                'man_month' => ['required', 'numeric', 'between:0.03,2'],
                'billing_rate' => ['required'],

            ];
        } else {
            $rules = [

                'hr_employee_id' => ['required'],
                'pr_position_id' => ['required'],
                'invoice_id' => ['required'],
                'month_year' => ['required', 'date', 'date_equals:' . $invoiceMonth, Rule::unique('pr_mm_utilizations')->where(fn ($query) => $query->where('hr_employee_id', request()->hr_employee_id)->where('id', '!=', $this->utilization_id))],
                'man_month' => ['required', 'numeric', 'between:0.03,2'],
                'billing_rate' => ['required'],
            ];
        }

        return $rules;
    }

    public function messages()
    {

        return [

            'month_year.unique' => "This Employee already entered",
            'month_year.date_equals' => "Your Entered Invoice Month is not correct",



        ];
    }
}
