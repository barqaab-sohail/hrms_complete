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
            'App\Models\Hr\HrDesignation',                  //Final Model HrDocumentName
            'App\Models\Hr\HrPostingDesignation',          //Model Through Access Final Model (Immediate Model)
            'hr_posting_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_designation_id'                             //Forein Key in Immediate Model of Final Model
        );

    }
    
    public function hrDepartment(){
        return $this->hasOneThrough(
            'App\Models\Hr\HrDepartment',                  //Final Model HrDocumentName
            'App\Models\Hr\HrPostingDepartment',          //Model Through Access Final Model (Immediate Model)
            'hr_posting_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_department_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

    public function hrSalary(){
        return $this->hasOneThrough(
            'App\Models\Hr\HrSalary',                  //Final Model HrDocumentName
            'App\Models\Hr\HrPostingSalary',          //Model Through Access Final Model (Immediate Model)
            'hr_posting_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_salary_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

    public function hrManager(){
        return $this->hasOneThrough(
            'App\Models\Hr\HrEmployee',                  //Final Model HrDocumentName
            'App\Models\Hr\HrPostingManager',          //Model Through Access Final Model (Immediate Model)
            'hr_posting_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_manager_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

    public function project(){
        return $this->hasOneThrough(
            'App\Models\Project\PrDetail',                  //Final Model HrDocumentName
            'App\Models\Hr\HrPostingProject',          //Model Through Access Final Model (Immediate Model)
            'hr_posting_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'pr_detail_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

    public function office(){
        return $this->hasOneThrough(
            'App\Models\Office\Office',                  //Final Model HrDocumentName
            'App\Models\Hr\HrPostingOffice',          //Model Through Access Final Model (Immediate Model)
            'hr_posting_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'office_id'                             //Forein Key in Immediate Model of Final Model
        );

    }
    




}
