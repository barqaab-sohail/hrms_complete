<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class Membership extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;



	public function cvDetail()
    {
        return $this->belongsToMany('App\Models\Cv\CvDetail')
        ->withPivot('membership_number')
        ->withTimestamps();
    }

    // public function hrEmployee()
    // {
    //     return $this->belongsToMany('App\Models\Hr\HrEmployee')
    //     ->withPivot('membership_no', 'expiry')
    //     ->withTimestamps();
    // }
    //
}
