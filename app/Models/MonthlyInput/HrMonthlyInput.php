<?php

namespace App\Models\MonthlyInput;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrMonthlyInput extends Model implements Auditable
{
    
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['month', 'year','is_lock'];
}
