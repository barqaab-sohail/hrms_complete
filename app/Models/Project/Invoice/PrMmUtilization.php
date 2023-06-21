<?php

namespace App\Models\Project\Invoice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrMmUtilization extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['pr_detail_id', 'pr_position_id', 'hr_employee_id', 'month_year', 'man_month', 'billing_rate', 'remarks'];
}
