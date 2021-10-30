<?php

namespace App\Models\Project\Payment;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PaymentReceive extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
   	protected $fillable = ['invoice_id', 'amount','date','cheque_no','cheque_date','withholding_amount','withholding_reason'];
}
