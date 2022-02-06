<?php

namespace App\Models\Input;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class InputProject extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['input_month_id', 'pr_detail_id','is_lock'];


    public function inputMonth(){

        return $this->belongsTo('App\Models\Input\InputMonth');
    }

    public function prDetail(){

        return $this->belongsTo('App\Models\Project\PrDetail');

    }

    public function hrEmployee(){
             return $this->hasOneThrough(
            'App\Models\Hr\HrEmployee',                  //Final Model l
            'App\Models\Input\Input',              //Model Through Access Final Model (Immediate Model)
            'input_project_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_employee_id'                             //Forein Key in Immediate Model of Final Model
        );
    }
     public function hrDesignation(){
             return $this->hasOneThrough(
            'App\Models\Hr\HrDesignation',          //Final Model l
            'App\Models\Input\Input',              //Model Through Access Final Model (Immediate Model)
            'input_project_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_designation_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

    public function monthlyInputEmployee(){
            return $this->hasMany('App\Models\Input\Input');
    }

    public function input(){
            return $this->hasOne('App\Models\Input\Input');
    }
}
