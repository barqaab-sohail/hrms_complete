<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsLocation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['asset_id','office_id','hr_employee_id','date'];

    public function asOffice(){
        return $this->hasOne('App\Models\Office\Office','id','office_id');
    }

}
