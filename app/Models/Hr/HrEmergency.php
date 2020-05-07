<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrEmergency extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['name','relation','mobile','address','hr_employee_id'];



    public function hrEmployee(){

        return $this->belongsTo('App\Models\Hr\HrEmployee');

    }
}
