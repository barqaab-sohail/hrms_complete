<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrPromotionDepartment extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['hr_promotion_id','hr_department_id'];

    public function hrDepartment(){
        return $this->belongsTo('App\Models\Hr\HrDepartment');
    }
}
