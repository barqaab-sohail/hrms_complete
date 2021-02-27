<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class InvoicePeriod extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['invoice_id','from','to'];

}
