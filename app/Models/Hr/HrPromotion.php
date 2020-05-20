<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrPromotion extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['hr_employee_id', 'hr_manager_id','hr_department_id','hr_salary_id','hr_designation_id','hr_documentation_id','effective_date','grade','category','remarks'];




    public function hrDesignation(){

        return $this->belongsTo('App\Models\Hr\HrDesignation');

    }

    public function hrSalary(){

        return $this->belongsTo('App\Models\Hr\HrSalary');

    }
}
