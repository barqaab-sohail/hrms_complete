<?php

namespace App\Models\Project\Progress;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrActualVsSchedule extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['pr_detail_id', 'pr_contractor_id', 'month', 'schedule_progress', 'actual_progress', 'current_month_progress'];

    public function prContractor()
    {
        return $this->hasOne('App\Models\Project\Contractor\PrContractor', 'id', 'pr_contractor_id');
    }
    public function getMonthAttribute($value)
    {
        $date = \Carbon\Carbon::parse($value)->format('F-Y');
        return $date;
    }
}
