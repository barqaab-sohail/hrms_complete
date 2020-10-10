<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrPromotionGrade extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['hr_promotion_id','hr_grade_id'];

    public function hrGrade(){
        return $this->belongsTo('App\Models\Hr\HrGrade');
    }
}
