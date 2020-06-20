<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrPosting extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['hr_employee_id', 'pr_detail_id','hr_designation_id','hr_manager_id','hr_department_id','hr_salary_id','office_id','effective_date','remarks'];


    public function hrDesignation(){

        return $this->belongsTo('App\Models\Hr\HrDesignation');

    }

    public function hrSalary(){

        return $this->belongsTo('App\Models\Hr\HrSalary');

    }

}
