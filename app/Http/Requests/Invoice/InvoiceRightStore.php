<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRightStore extends FormRequest
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
        'hr_employee_id'=> 'required',
        'pr_detail_id'=> 'required|unique_with:invoice_rights,hr_employee_id'
        //'mobile' => 'required|unique:users,mobile,NULL,id,isd,' . $request->isd,
        ];

    
        return $rules;
    }

    public function messages()
    {
        
        return [
            'pr_detail_id.unique_with' => 'rights already given',
        ];
    }
}
