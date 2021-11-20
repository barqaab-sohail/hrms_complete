<?php

namespace App\Http\Requests\Project\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceStore extends FormRequest
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
              
            'invoice_date' => 'required',
            'invoice_no'=>'required|unique:invoices,invoice_no,'.$this->invoice_id,
            'amount' => 'required',
            'sales_tax' => 'required',
            'invoice_type_id' => 'required',
            'document'=>'nullable|file|max:300'
        ];

    return $rules;
    }
}
