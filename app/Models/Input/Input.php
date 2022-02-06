<?php

namespace App\Models\Input;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Input extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['input_project_id', 'hr_employee_id','hr_designation_id','input','remarks'];

    public function hrEmployee(){
        return $this->belongsTo('App\Models\Hr\HrEmployee','hr_employee_id');
    }

    public function hrDesignation(){
        return $this->belongsTo('App\Models\Hr\HrDesignation','hr_designation_id');
    }
}
