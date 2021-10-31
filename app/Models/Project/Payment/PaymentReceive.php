<?php

namespace App\Models\Project\Payment;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PaymentReceive extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
   	protected $fillable = ['invoice_id','pr_detail_id','payment_status_id', 'amount','date','cheque_no','cheque_date'];


   	Public function invoice(){

        return $this->belongsTo('App\Models\Project\Invoice\Invoice' );

    }
}
