<?php

namespace App\Models\Project\Invoice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrDirectCostUtilization extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['pr_detail_id', 'direct_cost_detail_id', 'invoice_id', 'month_year', 'amount', 'remarks'];

    public function getMonthYearAttribute($value)
    {
        $date = \Carbon\Carbon::parse($value)->format('F-Y');
        return $date;
    }

    public function directCostDescription()
    {
        return $this->hasOneThrough(
            'App\Models\Common\DirectCostDescription', //Final Model HrDocumentName
            'App\Models\Project\Invoice\DirectCostDetail', //Model Through Access Final Model (Immediate Model)
            'id',                            //Forein Key in Immediate Model of This Model
            'id',                          //Final Model Primary Key
            'direct_cost_detail_id',             //current model Primary Key of Final Model (it is called foreign key)
            'direct_cost_description_id'           //Forein Key in Immediate Model of Final Model
        );
    }
}
