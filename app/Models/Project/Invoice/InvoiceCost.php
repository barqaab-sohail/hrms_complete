<?php

namespace App\Models\Project\Invoice;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class InvoiceCost extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
   	protected $fillable = ['invoice_id','amount','sales_tax'];
}
