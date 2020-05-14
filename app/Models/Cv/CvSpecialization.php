<?php

namespace App\Models\Cv;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class CvSpecialization extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
    
    protected $fillable = [
        'name', 
    ];


    public function cvExperience()
    {
        return $this->hasMany('App\Models\Cv\CvExperience');
    }
}
