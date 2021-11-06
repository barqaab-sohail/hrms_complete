<?php

namespace App\Models\Project\Payment;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PaymentDeduction extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
   	protected $fillable = ['payment_receive_id', 'pr_detail_id','withholding_tax','sales_tax','others','remarks'];
}
