<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AuditResultStore extends FormRequest
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
     
        'total_records'=> 'required|numeric',
        ];

        return $rules;
    }

    protected function getValidatorInstance()
    {
    $data = $this->all();
    $data['total_records'] = (int)str_replace(',', '',$this->total_records);
    $this->getInputSource()->replace($data);

    /*modify data before send to validator*/
    return parent::getValidatorInstance();
    }
}
