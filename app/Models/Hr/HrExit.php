<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class HrExit extends Model implements Auditable
{ 
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['hr_employee_id','hr_status_id','effective_date','reason','remarks'];


    public function hrStatus(){

    	return $this->belongsTo('App\Models\Hr\HrStatus');
    }

}
