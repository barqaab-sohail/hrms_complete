<?php

namespace App\Http\Requests\Project\Payment;

use Illuminate\Foundation\Http\FormRequest;

class PaymentStore extends FormRequest
{
    
   public function getValidatorInstance()
    {
        $this->cleanAmount();
        return parent::getValidatorInstance();
    }

    protected function cleanAmount()
    {
        if($this->request->has('amount')){
            $this->merge([
                'amount' => intval(str_replace( ',', '', $this->request->get('amount')))
            ]);
        }

        if($this->request->has('total_invoice_value')){
            $this->merge([
                'total_invoice_value' => intval(str_replace( ',', '', $this->request->get('total_invoice_value')))
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
              
            'invoice_id' => 'required',
            'amount' => "required|lte:total_invoice_value",
            'total_invoice_value'=>'required',
            'payment_date' => 'required',
            'payment_status_id' => 'required',
            'withholding_tax'=> 'required',
            'sales_tax'=> 'required',
            'other_deduction'=> 'required'
    
        ];

        return $rules;
    }



    public function messages()
    {
        
        return [
            'invoice_id.required' => ' Invoice No is required',
            'amount.lte' => ' Net Amount Recived is less than or equal to '.addComma($this->total_invoice_value),
            
           
        ];
    }
}
