<?php

namespace App\Http\Requests\Project\Payment;

use Illuminate\Foundation\Http\FormRequest;

class PaymentStore extends FormRequest
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
              
            'invoice_id' => 'required',
            'amount' => 'required',
            'payment_date' => 'required',
            'payment_status_id' => 'required',
    
        ];

        return $rules;
    }
}
