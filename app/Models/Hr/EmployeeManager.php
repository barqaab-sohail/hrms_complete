<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeManager extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['hr_employee_id','hr_manager_id','effective_date'];


    public function hrEmployee(){
            return $this->belongsTo('App\Models\Hr\HrEmployee','hr_manager_id');
    }

    public function hodDesignation(){
        return $this->hasManyThrough(
            'App\Models\Hr\HrDesignation', //Final Model HrDocumentName
            'App\Models\Hr\EmployeeDesignation', //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',              //Forein Key in Immediate Model of This Model
            'id',                          //Final Model Primary Key
            'hr_manager_id',	//current model Primary Key of Final Model (it is called foreign key)
            'hr_designation_id'           //Forein Key in Immediate Model of Final Model
        );
    }
}
