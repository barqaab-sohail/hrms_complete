<?php

namespace App\Http\Requests\Project\Invoice;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project\PrDetail;

class InvoiceStore extends FormRequest
{

    public function getValidatorInstance()
    {
        $this->cleanData();
        return parent::getValidatorInstance();
    }

    protected function cleanData()
    {
        if ($this->fee) {
            $this->merge([
                'fee' => intval(str_replace(',', '', $this->fee))
            ]);
        }
        if ($this->overhead) {
            $this->merge([
                'overhead' => intval(str_replace(',', '', $this->overhead))
            ]);
        }
        if ($this->amount) {
            $this->merge([
                'amount' => intval(str_replace(',', '', $this->amount))
            ]);
        }
        if ($this->sales_tax) {
            $this->merge([
                'sales_tax' => intval(str_replace(',', '', $this->sales_tax))
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
        $overhead = false;
        $prDetail = PrDetail::find(session('pr_detail_id'));
        if ($prDetail->contract_type_id == 2 && ($this->invoice_type_id == 1 || $this->invoice_type_id == 3)) {
            $overhead = true;
        }


        $rules = [

            'invoice_date' => 'required',
            'invoice_no' => 'required|unique:invoices,invoice_no,' . $this->invoice_id,
            'amount' => 'required',
            'remarks' => 'nullable|max:191',
            'sales_tax' => 'required|lt:amount',
            'invoice_type_id' => 'required',
            'document' => 'nullable|file|max:300'
        ];

        if ($overhead) {
            $rules += ['overhead' => 'required|lt:amount'];
            $rules += ['fee' => 'required|lt:amount'];
        } else {
            $rules += ['overhead' => 'nullable|lt:amount'];
            $rules += ['fee' => 'nullable|lt:amount|required_with:overhead'];
        }


        return $rules;
    }
}
