<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Hr\HrAppointment;


class HrEmployee extends Model implements Auditable
{
    
    use \OwenIt\Auditing\Auditable;
    protected $guarded = [];


    //default value of hr_status_id=1
    protected $attributes = [
        'hr_status_id' => 1 //default value of hr_status_id is 1 
    ];
    
    //get value of hr_status_id and show in string as per statusOptions
    public function getHrStatusIdAttribute($attribute)
    {
        return $this->statusOptions()[$attribute];
    }
    
    public function statusOptions()
    {
        return [
            1 => 'Onboard',
            2 => 'Resigned',
            3 => 'Terminated',
            4 => 'Retired',
            5 => 'Long Leave',
            6 => 'ManMonth Ended'

        ];
    }




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


    public function degreeAbove12(){
             return $this->hasManyThrough(
            'App\Models\Common\Education',                  //Final Model l
            'App\Models\Hr\HrEducation',              //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'education_id'                             //Forein Key in Immediate Model of Final Model
        )->where('educations.level','>',12);

    }

    public function degreeYearAbove12(){
            return $this->hasMany('App\Models\Hr\HrEducation')->join('educations', function($join)
                {
                     $join->on('hr_educations.education_id', '=', 'educations.id');

                })
                ->where('educations.level','>',12);

    }



    public function hrAppointment(){

        return $this->hasOne('App\Models\Hr\HrAppointment');

    }

    public function hrMembership(){

        return $this->hasOne('App\Models\Hr\HrMembership');

    }

    public function hrDriving(){

        return $this->hasOne('App\Models\Hr\HrDriving');

    }

     public function hrPassport(){

        return $this->hasOne('App\Models\Hr\HrPassport');

    }

     public function hrDisability(){

        return $this->hasOne('App\Models\Hr\HrDisability');

    }

    public function hrBloodGroup(){

        return $this->hasOne('App\Models\Hr\HrBloodGroup');

    }

    public function hrEmergency(){

        return $this->hasOne('App\Models\Hr\HrEmergency');

    }

    // public function employeeAppointmentProject(){
    //     return $this->hasOne('App\Models\Hr\EmployeeProject')->oldest();
    // }

    public function appointmentLetter(){
        return $this->hasManyThrough(
            'App\Models\Hr\HrDocumentName',                    //Final Model HrDocumentName
            'App\Models\Hr\HrDocumentNameDocumentation',      //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_document_name_id'                             //Forein Key in Immediate Model of Final Model
        )->where('hr_document_names.id',1);

    }

    public function cnicFront(){
        return $this->hasManyThrough(
            'App\Models\Hr\HrDocumentName',                    //Final Model HrDocumentName
            'App\Models\Hr\HrDocumentNameDocumentation',      //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_document_name_id'                             //Forein Key in Immediate Model of Final Model
        )->where('hr_document_names.id',2);

    }

    public function hrForm(){
        return $this->hasManyThrough(
            'App\Models\Hr\HrDocumentName',                    //Final Model HrDocumentName
            'App\Models\Hr\HrDocumentNameDocumentation',      //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_document_name_id'                             //Forein Key in Immediate Model of Final Model
        )->where('hr_document_names.id',4);

    }

    public function engineeringDegree(){
        return $this->hasManyThrough(
            'App\Models\Hr\HrDocumentName',                    //Final Model HrDocumentName
            'App\Models\Hr\HrDocumentNameDocumentation',      //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_document_name_id'                             //Forein Key in Immediate Model of Final Model
        )->where('hr_document_names.id',6);

    }

    public function educationalDocuments(){
        return $this->hasManyThrough(
            'App\Models\Hr\HrDocumentName',                    //Final Model HrDocumentName
            'App\Models\Hr\HrDocumentNameDocumentation',      //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_document_name_id'                             //Forein Key in Immediate Model of Final Model
        )->where('hr_document_names.id',11);

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
