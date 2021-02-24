<?php

namespace App\Models\Invoice;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class InvoiceRight extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $fillable = ['hr_employee_id','pr_detail_id'];


    public function hrEmployee(){
            return $this->belongsTo('App\Models\Hr\HrEmployee');
    }

    public function prDetail(){
            return $this->belongsTo('App\Models\Project\PrDetail');
    }
}
