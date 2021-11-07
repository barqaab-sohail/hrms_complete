<?php

namespace App\Models\Project\Progress;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrMonthlyProgress extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
   	protected $fillable = ['pr_detail_id','pr_progress_activity_id','completed', 'targeted','date'];

}
