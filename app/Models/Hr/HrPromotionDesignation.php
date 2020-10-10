<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrPromotionDesignation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['hr_promotion_id','hr_designation_id'];

    public function hrDesignation(){
        return $this->belongsTo('App\Models\Hr\HrDesignation');
    }
}
