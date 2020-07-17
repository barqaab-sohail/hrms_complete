<?php

namespace App\Models\Self;

use Illuminate\Database\Eloquent\Model;

class SsContact extends Model
{
     protected $fillable = ['hr_employee_id','name','designation','address','remarks'];


    public function mobiles()
    {
        return $this->hasMany('App\Models\Self\SsContactMobile');
        
    }

    public function emails()
    {
        return $this->hasMany('App\Models\Self\SsContactEmail');
        
    }
    public function office()
    {
        return $this->hasOne('App\Models\Self\SsContactOffice');
        
    }

}
