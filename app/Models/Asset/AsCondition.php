<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsCondition extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['asset_id','as_condition_type_id', 'condition_date'];


    public function asConditionType(){

        return $this->hasOne('App\Models\Asset\AsConditionType', 'id','as_condition_type_id'
            );
    }
}
