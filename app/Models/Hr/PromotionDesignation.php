<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PromotionDesignation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['hr_promotion_id','employee_designation_id'];
}
