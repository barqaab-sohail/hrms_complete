<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrPromotion extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['hr_employee_id','hr_documentation_id','effective_date','remarks'];


    public function hrDesignation(){
        return $this->hasOneThrough(
            'App\Models\Hr\EmployeeDesignation',                  //Final Model HrDocumentName
            'App\Models\Hr\PromotionDesignation',          //Model Through Access Final Model (Immediate Model)
            'hr_promotion_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'employee_designation_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

    public function hrSalary(){
        return $this->hasOneThrough(
            'App\Models\Hr\EmployeeSalary',                  //Final Model HrDocumentName
            'App\Models\Hr\PromotionSalary',          //Model Through Access Final Model (Immediate Model)
            'hr_promotion_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'employee_salary_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

    public function hrGrade(){
        return $this->hasOneThrough(
            'App\Models\Hr\EmployeeGrade',                  //Final Model HrDocumentName
            'App\Models\Hr\PromotionGrade',          //Model Through Access Final Model (Immediate Model)
            'hr_promotion_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'employee_grade_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

    public function hrManager(){
        return $this->hasOneThrough(
            'App\Models\Hr\EmployeeManager',                  //Final Model HrDocumentName
            'App\Models\Hr\PromotionManager',          //Model Through Access Final Model (Immediate Model)
            'hr_promotion_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'employee_manager_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

    
    public function hrDepartment(){
        return $this->hasOneThrough(
            'App\Models\Hr\EmployeeDepartment',                  //Final Model HrDocumentName
            'App\Models\Hr\PromotionDepartment',          //Model Through Access Final Model (Immediate Model)
            'hr_promotion_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'employee_department_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

    
    public function hrCategory(){
        return $this->hasOneThrough(
            'App\Models\Hr\EmployeeCategory',                  //Final Model HrDocumentName
            'App\Models\Hr\PromotionCategory',          //Model Through Access Final Model (Immediate Model)
            'hr_promotion_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'employee_category_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

    public function HrDocumentation(){
        return $this->belongsTo('App\Models\Hr\HrDocumentation');
    }


}
