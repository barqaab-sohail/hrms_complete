<?php

namespace App\Models\Project\Progress;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrDelayReason extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['pr_detail_id', 'pr_contractor_id', 'reason'];

    public function prContractor()
    {
        return $this->hasOne('App\Models\Project\Contractor\PrContractor', 'id', 'pr_contractor_id');
    }
}
