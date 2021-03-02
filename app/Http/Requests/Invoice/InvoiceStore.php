<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceStore extends FormRequest
{

    private $data;
    public function __construct(\Illuminate\Http\Request $request)
    {
        
      //removing comma and convert to integer value
       $request->merge(['cost' =>  (int)preg_replace('/[\,]/', '', $request->cost),
                        'sales_tax' =>  (int)preg_replace('/[\,]/', '', $request->sales_tax),
                        'total' =>  (int)str_replace( ',', '', $request->total ),
                        'esc_cost' =>  (int)preg_replace('/[\,]/', '', $request->esc_cost),
                        'esc_sales_tax' =>  (int)preg_replace('/[\,]/', '', $request->esc_sales_tax),
                        'esc_total' =>  (int)str_replace( ',', '', $request->esc_total),]);

       $data = $request;
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
        'pr_detail_id'=> 'required',
        'invoice_type_id'=> 'required',
        'invoice_no'=> 'required|unique:invoices,invoice_no,'.session('edit_invoice'),
        'cost'=>'required|integer|between:300,9999999999',
        'sales_tax'=>'required|lt:cost|integer|between:50,9999999999',
        'total'=>'required|numeric',
        'from'=>'nullable|date',
        'to'=>'nullable|date|after_or_equal:from',
        'esc_cost'=>'nullable|integer|lt:cost',
        'esc_sales_tax'=>'nullable|integer|lte:esc_cost',
        'esc_total'=>'nullable|integer|lt:total',

        //'mobile' => 'required|unique:users,mobile,NULL,id,isd,' . $request->isd,
        ];

    
        return $rules;
    }

    public function messages()
    {
        $invoiceNo = $this->invoice_no;
        return [
            'esc_cost.lt' => 'Escalation cost value must be less than invoice cost',
            'esc_sales_tax.lt' => 'Escalation sales tax value must be less than invoice sales tax value',
            'esc_total.lt' => 'Escalation total value must be less than invoice total value',
            'invoice_no.unique' => "$invoiceNo invoice no has already saved",
        ];
    }


}
