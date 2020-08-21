<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrDriving extends Model implements Auditable
{
    
	use \OwenIt\Auditing\Auditable;
    protected $fillable = ['hr_employee_id','licence_no','licence_expiry'];

}
