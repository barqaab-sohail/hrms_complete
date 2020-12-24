<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrMonthlyReportProject extends Model implements Auditable
{
     use \OwenIt\Auditing\Auditable;
	//protected $table = 'hr_memberships';
    protected $fillable = ['hr_monthly_report_id','pr_detail_id','is_locak'];
}
