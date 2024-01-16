<?php

namespace App\Models\Project\Invoice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class PrMmUtilization extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['pr_detail_id', 'pr_position_id',  'invoice_id', 'hr_employee_id', 'month_year', 'man_month', 'billing_rate', 'remarks'];


    public function getMonthYearAttribute($value)
    {
        $date = \Carbon\Carbon::parse($value)->format('F-Y');
        return $date;
    }

    public function hrDesignation()
    {

        return $this->hasOneThrough(
            'App\Models\Hr\HrDesignation', //Final Model HrDocumentName
            'App\Models\Project\PrPosition', //Model Through Access Final Model (Immediate Model)
            'id',                            //Forein Key in Immediate Model of This Model
            'id',                          //Final Model Primary Key
            'pr_position_id',             //current model Primary Key of Final Model (it is called foreign key)
            'hr_designation_id'           //Forein Key in Immediate Model of Final Model
        );

        //return $this->hasOneThrough('App\Models\Hr\HrDesignation', 'App\Models\Project\PrPosition');
        //return $this->belongsTo('App\Models\Hr\HrDesignation');
    }

    public function hrEmployee()
    {
        return $this->belongsTo('App\Models\Hr\HrEmployee');
    }
}
