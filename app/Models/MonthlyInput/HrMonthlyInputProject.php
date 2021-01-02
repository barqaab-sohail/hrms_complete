<?php

namespace App\Models\MonthlyInput;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrMonthlyInputProject extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['hr_monthly_input_id', 'pr_detail_id','lock_user','lock_manager'];


    public function hrMonthlyInput(){

        return $this->belongsTo('App\Models\MonthlyInput\HrMonthlyInput');

    }

    public function prDetail(){

        return $this->belongsTo('App\Models\Project\PrDetail');

    }

}
