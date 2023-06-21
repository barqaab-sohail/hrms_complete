<?php

namespace App\Models\Project\Invoice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrDirectCostUtilization extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['pr_detail_id', 'direct_cost_description_id', 'month_year', 'amount', 'remarks'];
}
