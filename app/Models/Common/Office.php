<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class Office extends Model implements Auditable
{ 
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['name','establish_date','address','country_id','state_id','city_id','is_active'];

    public function officePhones(){
    	return $this->hasMany('App\Models\Common\OfficePhone');
    }
}
