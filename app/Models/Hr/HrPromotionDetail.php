<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrPromotionDetail extends Model implements Auditable
{
   use \OwenIt\Auditing\Auditable;

    protected $fillable = ['hr_promotion_id', 'hr_manager_id','hr_department_id','hr_salary_id','hr_designation_id'];

}