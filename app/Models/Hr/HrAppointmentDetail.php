<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class HrAppointmentDetail extends Model implements Auditable
{
   use \OwenIt\Auditing\Auditable;

    protected $fillable = ['hr_appointment_id', 'hr_manager_id','hr_department_id','hr_salary_id','hr_designation_id'];

}