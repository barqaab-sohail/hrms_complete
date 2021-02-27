<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Invoice extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['pr_detail_id','invoice_type_id','invoice_status_id','invoice_no','invoice_date','description','reference'];



}
