<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class PrPositionStore extends FormRequest
{

    public function getValidatorInstance()
    {
        $this->removeComma();
        return parent::getValidatorInstance();
    }

    protected function removeComma()
    {

        if ($this->billing) {
            $this->merge([
                'billing' => (int)str_replace(',', '', $this->billing)
            ]);
        }
        if ($this->total_amount) {
            $this->merge([
                'total_amount' => (int)str_replace(',', '', $this->total_amount)
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
        return [
            'nominated_person' => 'required',
            'hr_designation_id' => 'required',
            'pr_position_type_id' => 'required|numeric',
            'total_mm' => 'required|numeric|min:0.001|max:6000',
            'billing' => 'required',
            'total_amount' => 'required',
            'remarks' => 'nullable|max:191'
        ];
    }
}
