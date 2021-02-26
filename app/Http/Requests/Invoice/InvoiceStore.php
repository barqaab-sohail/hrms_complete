<?php

namespace App\Http\Requests\Invoice;

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
        'pr_detail_id'=> 'required',
        'invoice_type_id'=> 'required',
        'invoice_no'=> 'required|unique:invoices',
        'cost'=>'required|numeric',

        //'mobile' => 'required|unique:users,mobile,NULL,id,isd,' . $request->isd,
        ];

    
        return $rules;
    }
}
