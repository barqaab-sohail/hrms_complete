<?php

namespace App\Models\Self;

use Illuminate\Database\Eloquent\Model;

class SsContact extends Model
{
     protected $fillable = ['hr_employee_id','name','designation','address','remarks'];


    public function mobile()
    {
        return $this->hasMany('App\Models\Self\SsContactMobile');
        
    }

}
