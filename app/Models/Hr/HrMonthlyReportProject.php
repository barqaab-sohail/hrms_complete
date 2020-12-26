<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrMonthlyReportProject extends Model implements Auditable
{
     use \OwenIt\Auditing\Auditable;
	//protected $table = 'hr_memberships';
    protected $fillable = ['hr_monthly_report_id','pr_detail_id','is_locak'];


    public function hrMonthlyReport(){

            return $this->belongsTo('App\Models\Hr\HrMonthlyReport');

    }

    public function prDetail(){

            return $this->belongsTo('App\Models\Project\PrDetail');

    }


}
