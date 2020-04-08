<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class HrAppointment extends Model
{
    protected $fillable = ['hr_employee_id', 'pr_detail_id','hr_letter_type_id','reference_no','joining_date','expiry_date','remarks'];

}
