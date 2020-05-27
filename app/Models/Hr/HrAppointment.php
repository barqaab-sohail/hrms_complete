<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class HrAppointment extends Model implements Auditable
{
   use \OwenIt\Auditing\Auditable;
    protected $fillable = ['hr_employee_id', 'pr_detail_id','hr_letter_type_id','hr_manager_id','hr_designation_id','hr_department_id','hr_salary_id','office_id','reference_no','joining_date','expiry_date','grade','category','remarks'];



    public function hrEmployee(){

        return $this->belongsTo('App\Models\Hr\HrEmployee');

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

