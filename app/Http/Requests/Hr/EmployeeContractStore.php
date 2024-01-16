<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeContractStore extends FormRequest
{

    public function getValidatorInstance()
    {
        $this->cleanDate();
        return parent::getValidatorInstance();
    }

    protected function cleanDate()
    {

        if ($this->to) {
            $this->merge([
                'to' => \Carbon\Carbon::parse($this->to)->format('Y-m-d')
            ]);
        }
        if ($this->from) {
            $this->merge([
                'from' => \Carbon\Carbon::parse($this->from)->format('Y-m-d')
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
            'from' => 'required|date',
            'to' => 'required|date|after:from',
        ];
        return $rules;
    }
}
