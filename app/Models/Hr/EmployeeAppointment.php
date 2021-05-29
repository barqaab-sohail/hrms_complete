<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeAppointment extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['hr_employee_id','hr_letter_type_id','reference_no','joining_date','expiry_date','hr_employee_type_id','remarks','hr_grade_id','hr_category_id','hr_department_id','hr_designation_id','hr_manager_id','office_id','pr_detail_id','hr_salary_id'];

    public function hrEmployee(){
        return $this->belongsTo('App\Models\Hr\HrEmployee');
    }

    public function getFormattedJoiningDateAttribute()
    {
        return \Carbon\Carbon::parse($this->joining_date)->format('d-M-Y');
    }

    // public function employeeGrade(){
    //     return $this->belongsTo('App\Models\Hr\EmployeeGrade');
    // }
    // public function employeeCategory(){
    //     return $this->belongsTo('App\Models\Hr\EmployeeCategory');
    // }
    // public function employeeDepartment(){
    //     return $this->belongsTo('App\Models\Hr\EmployeeDepartment');
    // }
    // public function employeeDesignation(){
    //     return $this->belongsTo('App\Models\Hr\EmployeeDesignation');
    // }
    // public function employeeManager(){
    //     return $this->belongsTo('App\Models\Hr\EmployeeManager');
    // }
    // public function employeeOffice(){
    //     return $this->belongsTo('App\Models\Hr\EmployeeOffice');
    // }
    // public function employeeProject(){
    //     return $this->belongsTo('App\Models\Hr\EmployeeProject');
    // }
    // public function employeeSalary(){
    //     return $this->belongsTo('App\Models\Hr\EmployeeSalary');
    // }


}
