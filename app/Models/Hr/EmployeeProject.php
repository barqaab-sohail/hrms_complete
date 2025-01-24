<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeProject extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['hr_employee_id', 'pr_detail_id','effective_date'];
}
