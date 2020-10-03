<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrPostingDepartment extends Model implements Auditable
{
    
	use \OwenIt\Auditing\Auditable;

    protected $fillable = ['hr_posting_id','hr_department_id'];

    public function hrDepartment(){
        return $this->belongsTo('App\Models\Hr\HrDepartment');
    }

}
