<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Invoice extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['pr_detail_id','invoice_type_id','invoice_status_id','invoice_no','invoice_date','description','reference'];


     protected $attributes = [
        'invoice_status_id' => 1 //default value of status_id is 1 
    ];
    
    //get value of status_id and show in string as per statusOptions
    public function getInvoiceStatusIdAttribute($attribute)
    {
        return $this->statusOptions()[$attribute];
    }
    
    public function statusOptions()
    {
        return [
            1 => 'Pending',
            2 => 'Received',
            3 => 'With Held',

        ];
    }

    public function invoiceCost(){
        return $this->hasOne('App\Models\Invoice\InvoiceCost');
    }

    public function invoicePeriod(){
        return $this->hasOne('App\Models\Invoice\InvoicePeriod');
    }


}
