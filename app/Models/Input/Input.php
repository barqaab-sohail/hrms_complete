<?php

namespace App\Models\Input;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Input extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['input_project_id', 'hr_employee_id','hr_designation_id','pr_detail_id','input','remarks'];

    
    protected $appends = ['office_department_id'];
    function getOfficeDepartmentIdAttribute() {
        return $this->officeDeparment->id??'';
    }

    public function hrEmployee(){
        return $this->belongsTo('App\Models\Hr\HrEmployee','hr_employee_id');
    }

    public function hrDesignation(){
        return $this->belongsTo('App\Models\Hr\HrDesignation','hr_designation_id');
    }

    public function inputProject(){
    	return $this->belongsTo('App\Models\Input\InputProject');
    }
    
    public function prDetail(){
        return $this->belongsTo('App\Models\Project\PrDetail','pr_detail_id');
    }


    public function officeDeparment(){
         
        //hasOneThrough Inverse
         return $this->hasOneThrough(
            'App\Models\Office\OfficeDepartment',
            'App\Models\Input\InputOfficeDepartment',
            'input_id',
            'id',
            'id',
            'office_department_id'
            );
    }

    public function inputOfficeDeparment(){
         return $this->hasOne('App\Models\Input\InputOfficeDepartment'
            );
    }


    // public function prDetail(){
    //     return $this->user->country;
    // }

    
}
