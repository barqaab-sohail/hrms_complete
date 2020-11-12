<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrConsultancyCost extends Model implements Auditable
{
    
    use \OwenIt\Auditing\Auditable;
    
   protected $fillable = ['pr_detail_id', 'pr_cost_type_id','partner_id','mm_cost','direct_cost','tax','total'];


}
