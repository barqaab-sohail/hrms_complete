<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrConsultancyCost extends Model implements Auditable
{
    
    use \OwenIt\Auditing\Auditable;
    
   protected $fillable = ['pr_detail_id', 'pr_cost_type_id','total_cost','remarks'];


   public function prManMonthCost(){

        return $this->hasOne('App\Models\Project\PrManMonthCost');

    }

    public function prDirectCost(){

        return $this->hasOne('App\Models\Project\PrDirectCost');

    }

    public function prSalesTax(){

        return $this->hasOne('App\Models\Project\PrSalesTax');

    }

     public function prContingency(){

        return $this->hasOne('App\Models\Project\PrContingency');

    }

    public function prCostType(){

        return $this->belongsTo('App\Models\Project\PrCostType', 'pr_cost_type_id');

    }


}
