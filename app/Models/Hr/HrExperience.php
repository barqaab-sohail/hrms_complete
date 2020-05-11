<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrExperience extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
	
	protected $table = 'hr_experiences';
    protected $fillable = ['hr_employee_id','organization','job_title','from','to','country_id','activities'];
}
