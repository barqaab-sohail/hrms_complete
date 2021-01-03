<?php

namespace App\Models\MonthlyInput;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrMonthlyInputProject extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['hr_monthly_input_id', 'pr_detail_id','lock_user','lock_manager'];


    public function hrMonthlyInput(){

        return $this->belongsTo('App\Models\MonthlyInput\HrMonthlyInput');

    }

    public function prDetail(){

        return $this->belongsTo('App\Models\Project\PrDetail');

    }

    public function hrEmployee(){
             return $this->hasManyThrough(
            'App\Models\Hr\HrEmployee',                  //Final Model l
            'App\Models\MonthlyInput\HrMonthlyInputEmployee',              //Model Through Access Final Model (Immediate Model)
            'hr_monthly_input_project_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_employee_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

    public function hrDesignation(){
             return $this->hasManyThrough(
            'App\Models\Hr\HrDesignation',                  //Final Model l
            'App\Models\MonthlyInput\HrMonthlyInputEmployee',              //Model Through Access Final Model (Immediate Model)
            'hr_monthly_input_project_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_designation_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

    public function hrMonthlyInputEmployee(){
            return $this->hasMany('App\Models\MonthlyInput\HrMonthlyInputEmployee');
    }

}
