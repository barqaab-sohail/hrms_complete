<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class HrSalary extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['total_salary'];
}
