<?php

namespace App\Models\Project\Invoice;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Invoice extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['pr_detail_id', 'invoice_type_id', 'invoice_no', 'invoice_date', 'description', 'reference', 'remarks'];

    public function paymentStatus()
    {

        return $this->hasOneThrough(
            'App\Models\Project\Payment\PaymentStatus',
            'App\Models\Project\Payment\PaymentReceive',
            'invoice_id',
            'id',
            'id',
            'payment_status_id'
        );
    }

    public function prDetail()
    {
        return $this->belongsTo('App\Models\Project\PrDetail');
    }

    public function invoiceType()
    {

        return $this->belongsTo('App\Models\Project\Invoice\InvoiceType');
    }


    public function invoiceCost()
    {

        return $this->hasOne('App\Models\Project\Invoice\InvoiceCost');
    }

    public function invoiceMonth()
    {
        return $this->hasOne('App\Models\Project\Invoice\InvoiceMonth');
    }

    public function invoiceDocument()
    {
        return $this->hasOne('App\Models\Project\Invoice\InvoiceDocument');
    }
}
