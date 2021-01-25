<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrManager extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
    protected $fillable = ['hr_employee_id','hod_id','effective_date'];


    public function hrEmployee(){
            return $this->belongsTo('App\Models\Hr\HrEmployee','hod_id');
    }

    public function hodDesignation(){
        return $this->hasOneThrough(
            'App\Models\Hr\HrDesignation', //Final Model HrDocumentName
            'App\Models\Hr\HrAppointment', //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',              //Forein Key in Immediate Model of This Model
            'id',                          //Final Model Primary Key
            'hod_id',	//current model Primary Key of Final Model (it is called foreign key)
            'hr_designation_id'           //Forein Key in Immediate Model of Final Model
        );
    }


}
