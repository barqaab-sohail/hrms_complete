<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrPromotionCategory extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['hr_promotion_id','hr_category_id'];

    public function hrCategory(){
        return $this->belongsTo('App\Models\Hr\HrCategory');
    }
}
