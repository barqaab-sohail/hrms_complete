<?php

namespace App\Models\Project\Invoice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class InvoiceMonth extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    
   	protected $fillable = ['invoice_id','invoice_month'];

   

    public function getInvoiceMonthAttribute($value) {
        $date = \Carbon\Carbon::parse($value)->format('F-Y');
        return $date;
    }
}
