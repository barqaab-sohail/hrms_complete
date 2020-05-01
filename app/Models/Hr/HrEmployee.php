<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class HrEmployee extends Model implements Auditable
{
    
    use \OwenIt\Auditing\Auditable;
    protected $guarded = [];


    public function user(){
        return $this->belongsTo('App\User');
    }


     public function education()
    {
        return $this->belongsToMany('App\Models\Common\Education')
        ->withPivot('country_id','institute','from','to','total_marks','marks_obtain','grade')
        ->withTimestamps();
    }


}
