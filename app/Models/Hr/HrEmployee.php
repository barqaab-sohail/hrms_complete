<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class HrEmployee extends Model implements Auditable
{
    
    use \OwenIt\Auditing\Auditable;
    protected $guarded = [];


    public function user(){
        return $this->belongsTo('App\User');
    }


    //  public function education()
    // {
    //     return $this->belongsToMany('App\Models\Common\Education')
    //     ->withPivot('id','country_id','institute','from','to','total_marks','marks_obtain','grade')
    //     ->withTimestamps();
    // }

    public function hrContactMobile(){

            return $this->hasOneThrough('App\Models\Hr\HrContactMobile', 'App\Models\Hr\HrContact');

    }

    public function hrContact(){

            return $this->hasMany('App\Models\Hr\HrContact');

    }

     public function hrEducation(){

            return $this->hasMany('App\Models\Hr\HrEducation');

    }



    public function hrAppointment(){

        return $this->hasOne('App\Models\Hr\HrAppointment');

    }

    public function hrEmergency(){

        return $this->hasOne('App\Models\Hr\HrEmergency');

    }

    public function documentName(){
        return $this->hasManyThrough(
            'App\Models\Hr\HrDocumentName',                    //Final Model HrDocumentName
            'App\Models\Hr\HrDocumentNameDocumentation',      //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_document_name_id'                             //Forein Key in Immediate Model of Final Model
        )->where('hr_document_names.id',1);

    }


    public function hrDesignation(){
        return $this->hasOneThrough(
            'App\Models\Hr\HrDesignation',                  //Final Model HrDocumentName
            'App\Models\Hr\HrAppointment',              //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_designation_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

    public function manager(){
        return $this->hasOneThrough(
            'App\Models\Hr\HrEmployee',                  //Final Model HrDocumentName
            'App\Models\Hr\HrAppointment',              //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_manager_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

    public function hrDepartment(){
        return $this->hasOneThrough(
            'App\Models\Hr\HrDepartment',                  //Final Model HrDocumentName
            'App\Models\Hr\HrAppointment',              //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_department_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

    public function hrSalary(){
        return $this->hasOneThrough(
            'App\Models\Hr\HrSalary',                  //Final Model HrDocumentName
            'App\Models\Hr\HrAppointment',              //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_salary_id'                             //Forein Key in Immediate Model of Final Model
        );

    }
    
   
}
