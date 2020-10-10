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
            'App\Models\Hr\HrDesignation',                  //Final Model HrDocumentName
            'App\Models\Hr\HrPromotionDesignation',          //Model Through Access Final Model (Immediate Model)
            'hr_promotion_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_designation_id'                             //Forein Key in Immediate Model of Final Model
        );

    }
    
    public function hrDepartment(){
        return $this->hasOneThrough(
            'App\Models\Hr\HrDepartment',                  //Final Model HrDocumentName
            'App\Models\Hr\HrPromotionDepartment',          //Model Through Access Final Model (Immediate Model)
            'hr_promotion_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_department_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

    public function hrSalary(){
        return $this->hasOneThrough(
            'App\Models\Hr\HrSalary',                  //Final Model HrDocumentName
            'App\Models\Hr\HrPromotionSalary',          //Model Through Access Final Model (Immediate Model)
            'hr_promotion_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_salary_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

    public function hrManager(){
        return $this->hasOneThrough(
            'App\Models\Hr\HrEmployee',                  //Final Model HrDocumentName
            'App\Models\Hr\HrPromotionManager',          //Model Through Access Final Model (Immediate Model)
            'hr_promotion_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_manager_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

    public function hrGrade(){
        return $this->hasOneThrough(
            'App\Models\Hr\HrGrade',                  //Final Model HrDocumentName
            'App\Models\Hr\HrPromotionGrade',          //Model Through Access Final Model (Immediate Model)
            'hr_promotion_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_grade_id'                             //Forein Key in Immediate Model of Final Model
        );

    }

    public function hrCategory(){
        return $this->hasOneThrough(
            'App\Models\Hr\HrCategory',                  //Final Model HrDocumentName
            'App\Models\Hr\HrPromotionCategory',          //Model Through Access Final Model (Immediate Model)
            'hr_promotion_id',                                 //Forein Key in Immediate Model of This Model
            'id',                                             //Final Model Primary Key
            'id',
            'hr_category_id'                             //Forein Key in Immediate Model of Final Model
        );

    }


}
