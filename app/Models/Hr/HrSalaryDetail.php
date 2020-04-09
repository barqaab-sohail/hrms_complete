<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class HrSalaryDetail extends Model
{
    protected $fillable = ['hr_employee_id', 'hr_salary_id','hr_common_model_id','category','grade'];
}
