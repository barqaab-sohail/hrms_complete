<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;



class HrEmployee extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['first_name', 'last_name', 'father_name', 'cnic', 'cnic_expiry', 'date_of_birth', 'employee_no', 'user_id', 'gender_id', 'hr_status_id', 'marital_status_id', 'religion_id', 'domicile_id'];

    protected $appends = ['full_name', 'designation', 'project'];

    function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    function getDesignationAttribute()
    {
        return $this->employeeCurrentDesignation->name ?? '';
    }
    function getProjectAttribute()
    {
        return $this->employeeCurrentProject->name ?? '';
    }

    public function getJoiningDateAttribute()
    {
        $joiningDate = $this->employeeAppointment->joining_date ?? '';
        return  $joiningDate ? \Carbon\Carbon::parse($joiningDate)->format('M d, Y') : '';
    }

    public function getLastWorkingDateAttribute()
    {
        $lastWorkingDate = $this->hrExit->effective_date ?? '';
        return  $lastWorkingDate ? \Carbon\Carbon::parse($lastWorkingDate)->format('M d, Y') : '';
    }


    public function hrEmployeeHusband()
    {
        return $this->hasOne('App\Models\Hr\HrEmployeeHusband');
    }


    // compare with existing data after update and send notification of admin 
    public function compareTo(HrEmployee $other)
    {

        $attributes = collect($this->getAttributes())
            ->map(function ($attribute, $key) use ($other) {
                if ($attribute != $other->$key) {
                    return $key = $attribute;
                }
            })->reject(function ($attribute, $key) {
                return !$attribute || in_array($key, ['id', 'hr_status_id', 'created_at', 'updated_at']);
            });

        return $attributes;
    }


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
            1 => 'Active',
            2 => 'Resigned',
            3 => 'Terminated',
            4 => 'Retired',
            5 => 'Long Leave',
            6 => 'ManMonth Ended',
            7 => 'Death'

        ];
    }

    public function getFormattedDateOfBirthAttribute()
    {
        return \Carbon\Carbon::parse($this->date_of_birth)->format('M d, Y');
    }


    public function user()
    {
        return $this->belongsTo('App\User');
    }


    //  public function education()
    // {
    //     return $this->belongsToMany('App\Models\Common\Education')
    //     ->withPivot('id','country_id','institute','from','to','total_marks','marks_obtain','grade')
    //     ->withTimestamps();
    // }

    public function hrContactMobile()
    {

        return $this->hasOneThrough('App\Models\Hr\HrContactMobile', 'App\Models\Hr\HrContact');
    }

    public function hrContactLandline()
    {

        return $this->hasOneThrough('App\Models\Hr\HrContactLandline', 'App\Models\Hr\HrContact');
    }

    public function hrEmergency()
    {

        return $this->hasOne('App\Models\Hr\HrEmergency');
    }

    public function hrExit()
    {
        return $this->hasOne('App\Models\Hr\HrExit')->orderBy('hr_exits.effective_date', 'desc');
    }

    public function employeeDesignation()
    {
        //return $this->hasOne('App\Models\Hr\EmployeeDesignation');
        return $this->hasManyThrough(
            'App\Models\Hr\HrDesignation', //Final Model HrDocumentName
            'App\Models\Hr\EmployeeDesignation', //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',              //Forein Key in Immediate Model of This Model
            'id',                          //Final Model Primary Key
            'id',   //current model Primary Key of Final Model (it is called foreign key)
            'hr_designation_id'           //Forein Key in Immediate Model of Final Model
        )->orderBy('employee_designations.id', 'asc');
    }

    public function employeeCurrentDesignation()
    {
        return $this->hasOneThrough(
            'App\Models\Hr\HrDesignation', //Final Model HrDocumentName
            'App\Models\Hr\EmployeeDesignation', //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',              //Forein Key in Immediate Model of This Model
            'id',                          //Final Model Primary Key
            'id',   //current model Primary Key of Final Model (it is called foreign key)
            'hr_designation_id'           //Forein Key in Immediate Model of Final Model
        )->orderBy('employee_designations.effective_date', 'desc');
    }

    public function employeeState()
    {
        return $this->hasManyThrough(
            'App\Models\Common\State', //Final Model HrDocumentName
            'App\Models\Hr\HrContact', //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',              //Forein Key in Immediate Model of This Model
            'id',                          //Final Model Primary Key
            'id',   //current model Primary Key of Final Model (it is called foreign key)
            'state_id'           //Forein Key in Immediate Model of Final Model
        )->orderBy('hr_contacts.id', 'asc');
    }


    public function employeeCategory()
    {
        return $this->hasManyThrough(
            'App\Models\Hr\HrCategory', //Final Model HrDocumentName
            'App\Models\Hr\EmployeeCategory', //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',              //Forein Key in Immediate Model of This Model
            'id',                          //Final Model Primary Key
            'id',   //current model Primary Key of Final Model (it is called foreign key)
            'hr_category_id'           //Forein Key in Immediate Model of Final Model
        )->orderBy('employee_categories.id', 'asc');
    }

    public function employeeCurrentCategory()
    {
        return $this->hasOneThrough(
            'App\Models\Hr\HrCategory', //Final Model HrDocumentName
            'App\Models\Hr\EmployeeCategory', //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',              //Forein Key in Immediate Model of This Model
            'id',                          //Final Model Primary Key
            'id',   //current model Primary Key of Final Model (it is called foreign key)
            'hr_category_id'           //Forein Key in Immediate Model of Final Model
        )->orderBy('employee_categories.effective_date', 'desc');
    }



    public function employeeOffice()
    {
        //return $this->hasOne('App\Models\Hr\EmployeeDesignation');
        return $this->hasManyThrough(
            'App\Models\Common\Office', //Final Model HrDocumentName
            'App\Models\Hr\EmployeeOffice', //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',              //Forein Key in Immediate Model of This Model
            'id',                          //Final Model Primary Key
            'id',   //current model Primary Key of Final Model (it is called foreign key)
            'office_id'           //Forein Key in Immediate Model of Final Model
        )->orderBy('employee_offices.id', 'asc');
    }

    public function employeeCurrentOffice()
    {
        //return $this->hasOne('App\Models\Hr\EmployeeDesignation');
        return $this->hasOneThrough(
            'App\Models\Common\Office', //Final Model HrDocumentName
            'App\Models\Hr\EmployeeOffice', //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',              //Forein Key in Immediate Model of This Model
            'id',                          //Final Model Primary Key
            'id',   //current model Primary Key of Final Model (it is called foreign key)
            'office_id'           //Forein Key in Immediate Model of Final Model
        )->orderBy('employee_offices.effective_date', 'desc');
    }

    public function employeeDepartment()
    {
        //return $this->hasOne('App\Models\Hr\EmployeeDesignation');
        return $this->hasManyThrough(
            'App\Models\Hr\HrDepartment', //Final Model HrDocumentName
            'App\Models\Hr\EmployeeDepartment', //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',              //Forein Key in Immediate Model of This Model
            'id',                          //Final Model Primary Key
            'id',   //current model Primary Key of Final Model (it is called foreign key)
            'hr_department_id'           //Forein Key in Immediate Model of Final Model
        )->orderBy('employee_departments.id', 'asc');
    }


    public function employeeProject()
    {
        return $this->hasManyThrough(
            'App\Models\Project\PrDetail',                  //Final Model HrDocumentName
            'App\Models\Hr\EmployeeProject',              //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'pr_detail_id'                             //Forein Key in Immediate Model of Final Model
        )->orderBy('employee_projects.id', 'asc');
    }

    public function employeeCurrentProject()
    {
        return $this->hasOneThrough(
            'App\Models\Project\PrDetail',                  //Final Model HrDocumentName
            'App\Models\Hr\EmployeeProject',              //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'pr_detail_id'                             //Forein Key in Immediate Model of Final Model
        )->orderBy('employee_projects.effective_date', 'desc');
    }

    public function hrContact()
    {
        return $this->hasMany('App\Models\Hr\HrContact');
    }

    public function hrDocumentations()
    {
        return $this->hasMany('App\Models\Hr\HrDocumentation');
    }

    public function hrContactPermanent()
    {
        return $this->hasOne('App\Models\Hr\HrContact')->where('hr_contact_type_id', '=', 1);
    }


    public function employeeCatA()
    {
        return $this->hasOne('App\Models\Hr\EmployeeCategory')->where('hr_category_id', '=', 1);
    }

    public function hrContactPermanentCity()
    {
        return $this->hasOneThrough(
            'App\Models\Common\City',                //Final Model l
            'App\Models\Hr\HrContact',              //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',
            'id',                                   //Final Model Primary Key
            'id',
            'city_id'
        )->where('hr_contact_type_id', '=', 1);
    }

    public function hrBloodGroup()
    {
        return $this->hasOneThrough(
            'App\Models\Common\BloodGroup',                  //Final Model HrDocumentName
            'App\Models\Hr\HrBloodGroup',              //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'blood_group_id'                             //Forein Key in Immediate Model of Final Model
        );
    }




    public function hrEducation()
    {

        return $this->hasMany('App\Models\Hr\HrEducation');
    }


    public function degreeAbove12()
    {
        return $this->hasManyThrough(
            'App\Models\Common\Education',                  //Final Model l
            'App\Models\Hr\HrEducation',              //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'education_id'                             //Forein Key in Immediate Model of Final Model
        )->where('educations.level', '>=', 12);
    }

    public function degreeYearAbove12()
    {
        return $this->hasMany('App\Models\Hr\HrEducation')->join('educations', function ($join) {
            $join->on('hr_educations.education_id', '=', 'educations.id');
        })
            ->where('educations.level', '>=', 12);
    }



    public function employeeAppointment()
    {

        return $this->hasOne('App\Models\Hr\EmployeeAppointment');
    }

    public function hrMembership()
    {

        return $this->hasOne('App\Models\Hr\HrMembership');
    }


    public function hrPassport()
    {

        return $this->hasOne('App\Models\Hr\HrPassport');
    }

    public function hrDisability()
    {

        return $this->hasOne('App\Models\Hr\HrDisability');
    }





    // public function employeeAppointmentProject(){
    //     return $this->hasOne('App\Models\Hr\EmployeeProject')->oldest();
    // }

    public function appointmentLetter()
    {
        return $this->hasManyThrough(
            'App\Models\Hr\HrDocumentName',                    //Final Model HrDocumentName
            'App\Models\Hr\HrDocumentNameDocumentation',      //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_document_name_id'                             //Forein Key in Immediate Model of Final Model
        )->where('hr_document_names.id', 1); // 1 belong to Appointment Letter
    }

    public function cnicFront()
    {
        return $this->hasManyThrough(
            'App\Models\Hr\HrDocumentName',                    //Final Model HrDocumentName
            'App\Models\Hr\HrDocumentNameDocumentation',      //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_document_name_id'                             //Forein Key in Immediate Model of Final Model
        )->where('hr_document_names.id', 2); // 2 belong to CNIC Front
    }

    public function hrForm()
    {
        return $this->hasManyThrough(
            'App\Models\Hr\HrDocumentName',                    //Final Model HrDocumentName
            'App\Models\Hr\HrDocumentNameDocumentation',      //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_document_name_id'                             //Forein Key in Immediate Model of Final Model
        )->where('hr_document_names.id', 4); // 5 belong to Hr Form
    }

    public function joiningReport()
    {
        return $this->hasManyThrough(
            'App\Models\Hr\HrDocumentName',                    //Final Model HrDocumentName
            'App\Models\Hr\HrDocumentNameDocumentation',      //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_document_name_id'                             //Forein Key in Immediate Model of Final Model
        )->where('hr_document_names.id', 5); // 5 belong to Joining Report
    }

    public function engineeringDegree()
    {
        return $this->hasManyThrough(
            'App\Models\Hr\HrDocumentName',                    //Final Model HrDocumentName
            'App\Models\Hr\HrDocumentNameDocumentation',      //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_document_name_id'                             //Forein Key in Immediate Model of Final Model
        )->where('hr_document_names.id', 6);
    }

    public function educationalDocuments()
    {
        return $this->hasManyThrough(
            'App\Models\Hr\HrDocumentName',                    //Final Model HrDocumentName
            'App\Models\Hr\HrDocumentNameDocumentation',      //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_document_name_id'                             //Forein Key in Immediate Model of Final Model
        )->where('hr_document_names.id', 11);
    }




    // public function hrDesignation(){
    //     return $this->hasOneThrough(
    //         'App\Models\Hr\HrDesignation',                  //Final Model HrDocumentName
    //         'App\Models\Hr\HrAppointment',              //Model Through Access Final Model (Immediate Model)
    //         'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
    //         'id',                                             //Final Model Primary Key
    //         'id',
    //         'hr_designation_id'                             //Forein Key in Immediate Model of Final Model
    //     );
    // }

    public function hrDesignation()
    {
        return $this->hasManyThrough(
            'App\Models\Hr\HrDesignation', //Final Model HrDocumentName
            'App\Models\Hr\EmployeeDesignation', //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',              //Forein Key in Immediate Model of This Model
            'id',                          //Final Model Primary Key
            'id',   //current model Primary Key of Final Model (it is called foreign key)
            'hr_designation_id'           //Forein Key in Immediate Model of Final Model
        );
    }

    public function hrDriving()
    {
        return $this->hasOne('App\Models\Hr\HrDriving');
    }

    public function manager()
    {
        return $this->hasOneThrough(
            'App\Models\Hr\HrEmployee',                  //Final Model HrDocumentName
            'App\Models\Hr\EmployeeAppointment',              //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_manager_id'                             //Forein Key in Immediate Model of Final Model
        );
    }

    public function hrDepartment()
    {
        return $this->hasOneThrough(
            'App\Models\Hr\HrDepartment',                  //Final Model HrDocumentName
            'App\Models\Hr\EmployeeAppointment',              //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_department_id'                             //Forein Key in Immediate Model of Final Model
        );
    }

    public function hrSalary()
    {
        return $this->hasOneThrough(
            'App\Models\Hr\HrSalary',                  //Final Model HrDocumentName
            'App\Models\Hr\EmployeeAppointment',              //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_salary_id'                             //Forein Key in Immediate Model of Final Model
        );
    }


    public function hod()
    {
        return $this->hasOne('App\Models\Hr\EmployeeManager')->orderBy('effective_date', 'DESC');
    }

    public function picture()
    {
        return $this->hasOne('App\Models\Hr\HrDocumentation')->where('description', '=', 'Picture');
    }

    public function employeePicture()
    {
        $picture = HrDocumentation::where([['description', 'Picture'], ['hr_employee_id', session('hr_employee_id')]])->first();
        if ($picture) {
            $picturePath = $picture->path . $picture->file_name;
        } else {
            $picturePath = '';
        }
        return $picturePath;
    }

    public function leAccumulative()
    {
        return $this->hasOne('App\Models\Leave\LeAccumulative');
    }

    public function employeeCurrentSalary()
    {
        return $this->hasOneThrough(
            'App\Models\Hr\HrSalary',                          //Final Model HrDocumentName
            'App\Models\Hr\EmployeeSalary',                     //Model Through Access Final Model (Immediate Model)
            'hr_employee_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_salary_id'                             //Forein Key in Immediate Model of Final Model
        )->orderBy('employee_salaries.effective_date', 'desc');
    }
}
