<?php

namespace App\Models\Leave;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Leave extends Model  implements Auditable
{
	use \OwenIt\Auditing\Auditable;
    protected $fillable = ['hr_employee_id', 'le_type_id','leave_date','from','to','days','reason','contact_no','address'];    
    
    public function hrEmployee(){

        return $this->belongsTo('App\Models\Hr\HrEmployee');

    }

    public function employeeDesignation(){
        //return $this->hasOne('App\Models\Hr\EmployeeDesignation');
        return $this->hasManyThrough(
            'App\Models\Hr\HrDesignation', //Final Model HrDocumentName
            'App\Models\Hr\EmployeeDesignation', //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',              //Forein Key in Immediate Model of This Model
            'id',                          //Final Model Primary Key
            'hr_employee_id',   //current model Primary Key of Final Model (it is called foreign key)
            'hr_designation_id'           //Forein Key in Immediate Model of Final Model
        )->orderBy('employee_designations.id', 'asc');
    }

}
