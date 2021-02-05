<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use DB;


class HrAppointment extends Model implements Auditable
{
   use \OwenIt\Auditing\Auditable;
    protected $fillable = ['hr_employee_id','hr_letter_type_id','hr_manager_id','reference_no','joining_date','expiry_date','hr_employee_type_id','remarks'];



    public function hrEmployee(){
        return $this->belongsTo('App\Models\Hr\HrEmployee');
    }

    // public function appointmentData($id){

    //     return $data = DB::table('hr_appointments')          
    //         ->join('employee_projects','hr_appointments.hr_employee_id','=','employee_projects.hr_employee_id')
    //         ->join('employee_designations','hr_appointments.hr_employee_id','=','employee_designations.hr_employee_id')
    //         ->join('employee_departments','hr_appointments.hr_employee_id','=','employee_departments.hr_employee_id')
    //         ->join('employee_managers','hr_appointments.hr_employee_id','=','employee_managers.hr_employee_id')
    //         ->join('employee_offices','hr_appointments.hr_employee_id','=','employee_offices.hr_employee_id')
    //         ->join('employee_categories','hr_appointments.hr_employee_id','=','employee_categories.hr_employee_id')
    //          ->join('employee_salaries','hr_appointments.hr_employee_id','=','employee_salaries.hr_employee_id')
    //          ->where('hr_appointments.hr_employee_id',$id)
    //          ->select('hr_appointments.*','employee_projects.pr_detail_id','employee_designations.hr_designation_id','employee_departments.hr_department_id','employee_managers.hod_id','employee_offices.office_id','employee_categories.hr_category_id','employee_salaries.hr_salary_id',)

       
    //         ->first();
    // }

    public function employeeGrade(){
        return $this->belongsTo('App\Models\Hr\EmployeeGrade','hr_employee_id','hr_employee_id');
    }
    public function employeeCategory(){
        return $this->belongsTo('App\Models\Hr\EmployeeCategory','hr_employee_id','hr_employee_id');
    }
    public function employeeDepartment(){
        return $this->belongsTo('App\Models\Hr\EmployeeDepartment','hr_employee_id','hr_employee_id');
    }
    public function employeeDesignation(){
        return $this->belongsTo('App\Models\Hr\EmployeeDesignation','hr_employee_id','hr_employee_id');
    }
    public function employeeManager(){
        return $this->belongsTo('App\Models\Hr\EmployeeManager','hr_employee_id','hr_employee_id');
    }
    public function employeeOffice(){
        return $this->belongsTo('App\Models\Hr\EmployeeOffice','hr_employee_id','hr_employee_id');
    }
    public function employeeProject(){
        return $this->belongsTo('App\Models\Hr\EmployeeProject','hr_employee_id','hr_employee_id');
    }
    public function employeeSalary(){
        return $this->belongsTo('App\Models\Hr\EmployeeSalary','hr_employee_id','hr_employee_id');
    }

    
    
    // // public function designation(){

    // // 	 return $this->hasOneThrough('App\Models\Hr\HrDesignation', 'App\Models\Hr\HrAppointmentDetail');
    // // }
   
    // public function appointmentDetail(){

    // 	return $this->hasOne('App\Models\Hr\HrAppointmentDetail');
    // }


    // public function designation(){

    // 	 return $this->belongsToThrough('App\Models\Hr\HrDesignation', 'App\Models\Hr\HrAppointmentDetail');
    // }

}

