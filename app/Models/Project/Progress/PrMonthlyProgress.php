<?php

namespace App\Models\Project\Progress;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrMonthlyProgress extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $table = 'pr_monthly_progresses';

   	protected $fillable = ['pr_detail_id','pr_progress_activity_id','scheduled', 'actual','date'];

   	Public function prProgressActivity(){

        return $this->belongsTo('App\Models\Project\Progress\PrProgressActivity' );

    }

}
