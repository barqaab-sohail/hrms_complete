<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrPosting extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['hr_employee_id', 'hr_documentation_id','effective_date','remarks'];

    public function hrDesignation(){
        return $this->hasOneThrough(
            'App\Models\Hr\EmployeeDesignation',                  //Final Model HrDocumentName
            'App\Models\Hr\PostingDesignation',          //Model Through Access Final Model (Immediate Model)
            'hr_posting_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'employee_designation_id'                             //Forein Key in Immediate Model of Final Model
        );

    }
    
    public function hrDepartment(){
        return $this->hasOneThrough(
            'App\Models\Hr\EmployeeDepartment',                  //Final Model HrDocumentName
            'App\Models\Hr\PostingDepartment',          //Model Through Access Final Model (Immediate Model)
            'hr_posting_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'employee_department_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

     public function hrSalary(){
        return $this->hasOneThrough(
            'App\Models\Hr\EmployeeSalary',                  //Final Model HrDocumentName
            'App\Models\Hr\PostingSalary',          //Model Through Access Final Model (Immediate Model)
            'hr_posting_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'employee_salary_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

   public function hrManager(){
        return $this->hasOneThrough(
            'App\Models\Hr\EmployeeManager',                  //Final Model HrDocumentName
            'App\Models\Hr\PostingManager',          //Model Through Access Final Model (Immediate Model)
            'hr_posting_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'employee_manager_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

    public function project(){
        return $this->hasOneThrough(
            'App\Models\Project\PrDetail',                  //Final Model HrDocumentName
            'App\Models\Hr\PostingProject',          //Model Through Access Final Model (Immediate Model)
            'hr_posting_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'employee_project_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

    public function office(){
        return $this->hasOneThrough(
            'App\Models\Office\Office',                  //Final Model HrDocumentName
            'App\Models\Hr\PostingOffice',          //Model Through Access Final Model (Immediate Model)
            'hr_posting_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'employee_office_id'                             //Forein Key in Immediate Model of Final Model
        );

    }
    




}
