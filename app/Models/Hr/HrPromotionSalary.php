<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrPromotionSalary extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['hr_promotion_id','hr_salary_id'];

    public function hrSalary(){
        return $this->belongsTo('App\Models\Hr\HrSalary');
    }
}
