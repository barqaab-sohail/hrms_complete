<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrMonthlyReport extends Model implements Auditable
{
    
    use \OwenIt\Auditing\Auditable;
	//protected $table = 'hr_memberships';
    protected $fillable = ['month','year','is_locak'];
}
