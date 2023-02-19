<?php

namespace App\Http\Requests\Project\Contractor;

use Illuminate\Foundation\Http\FormRequest;

class ContractorStore extends FormRequest
{


    public function getValidatorInstance()
    {
        $this->cleanDate();
        return parent::getValidatorInstance();
    }

    protected function cleanDate()
    {
        if ($this->contract_signing_date) {
            $this->merge([
                'contract_signing_date' => \Carbon\Carbon::parse($this->contract_signing_date)->format('Y-m-d'),
                'effective_date' => \Carbon\Carbon::parse($this->effective_date)->format('Y-m-d'),
                'contractual_completion_date' => \Carbon\Carbon::parse($this->contractual_completion_date)->format('Y-m-d')
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
            'contractor_name' => 'required|max:511',
            'contract_name' => 'required|max:511',
            'contract_signing_date' => 'required',
            'effective_date' => 'required|after_or_equal:contract_signing_date',
            'contractual_completion_date' => 'required|after:effective_date',
            'completion_period' => 'required|max:191',
            'contract_price' => 'required|max:511',
        ];

        return $rules;
    }
}
