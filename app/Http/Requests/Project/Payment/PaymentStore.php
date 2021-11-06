<?php

namespace App\Http\Requests\Project\Payment;

use Illuminate\Foundation\Http\FormRequest;

class PaymentStore extends FormRequest
{
    
     public function __construct(\Illuminate\Http\Request $request)
    {
        
        $this->amount =  removeComma($this->amount);
        $this->total_invoice_value =  removeComma($this->total_invoice_value);

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
            'amount' => 'required|max:'.$this->total_invoice_value,
            'total_invoice_value'=>'required',
            'payment_date' => 'required',
            'payment_status_id' => 'required',
    
        ];

        return $rules;
    }



    public function messages()
    {
        
        return [
            'invoice_id.required' => ' Invoice No is required',
            'amount.max' => 'Net Amount Received must be less than or equal to '.$this->total_invoice_value,
        ];
    }
}
