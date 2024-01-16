<?php

namespace App\Http\Requests\Project\Invoice;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DirectCostUtilizationStore extends FormRequest
{

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
        if ($this->amount) {
            $this->merge([
                'amount' => (int)str_replace(',', '', $this->amount)
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
        if (!$this->double_charging) {
            $rules = [

                'direct_cost_detail_id' => ['required'],
                'invoice_id' => ['required'],
                'month_year' => ['required', 'date'],

            ];
        }else{
            $rules = [

                'direct_cost_detail_id' => ['required'],
                'invoice_id' => ['required'],
                'month_year' => ['required', 'date', Rule::unique('pr_direct_cost_utilizations')->where(fn ($query) => $query->where('direct_cost_detail_id', request()->direct_cost_detail_id)->where('id', '!=', $this->utilization_id))],

            ];
        }

        return $rules;
    }
}
