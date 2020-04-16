<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class HrAppointment extends Model implements Auditable
{
   use \OwenIt\Auditing\Auditable;
    protected $fillable = ['hr_employee_id', 'pr_detail_id','hr_letter_type_id','reference_no','joining_date','expiry_date','remarks'];

}
