<?php

namespace App\Models\MonthlyInput;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrMonthlyInputEmployee extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['hr_monthly_input_project_id', 'hr_employee_id','hr_designation_id','input','remarks'];
}
