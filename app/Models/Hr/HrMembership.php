<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrMembership extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
	//protected $table = 'hr_memberships';
    protected $fillable = ['hr_employee_id','membership_id','membership_no','expiry'];
}
