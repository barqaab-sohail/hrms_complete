<?php

namespace App\Models\Project\Cost;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrCost extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    
   protected $fillable = ['pr_detail_id', 'pr_cost_type_id','mm_cost','direct_cost','contingency_cost','sales_tax','total_cost','remarks'];




   	public function prCostType(){
        return $this->belongsTo('App\Models\Project\Cost\PrCostType', 'pr_cost_type_id');

    }
}
