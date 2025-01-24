<?php

namespace App\Models\Project\Invoice;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class InvoiceDocument extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
   	protected $fillable = ['invoice_id','pr_detail_id',  'extension','path','size'];

}
