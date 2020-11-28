<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrConsultancyCost extends Model implements Auditable
{
    
    use \OwenIt\Auditing\Auditable;
    
   protected $fillable = ['pr_detail_id', 'pr_cost_type_id','partner_id'];


   public function prConsultancyCostMm(){

        return $this->hasOne('App\Models\Project\PrConsultancyCostMm');

    }

    public function prConsultancyCostDirect(){

        return $this->hasOne('App\Models\Project\PrConsultancyCostDirect');

    }

    public function prConsultancyCostTax(){

        return $this->hasOne('App\Models\Project\PrConsultancyCostTax');

    }

     public function prConsultancyCostContingency(){

        return $this->hasOne('App\Models\Project\PrConsultancyCostContingency');

    }

    public function prConsultancyCostType(){

        return $this->belongsTo('App\Models\Project\PrCostType', 'pr_cost_type_id');

    }

    public function prFirmName(){

        return $this->belongsTo('App\Models\Common\Partner', 'partner_id');

    }


    // public function prConsultancyCostType(){
    //     return $this->belongsTo('App\Models\Project\PrCostType');
    // }


}
