<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrConsultancyCostMm extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
   protected $fillable = ['pr_consultancy_cost_id', 'man_month_cost'];
}
