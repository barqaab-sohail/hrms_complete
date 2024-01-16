<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class HrContact extends Model implements Auditable
{
    
   use \OwenIt\Auditing\Auditable;
    protected $fillable = ['hr_employee_id', 'hr_contact_type_id', 'house','street','town','tehsile','city_id','state_id','country_id'];

    

    public function hrContactType()
    {
        return $this->belongsTo('App\Models\Hr\HrContactType');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Common\Country');
    }

    public function state()
    {
        return $this->belongsTo('App\Models\Common\State');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\Common\City');
    }

    public function email()
    {
        return $this->hasOne('App\Models\Hr\HrContactEmail');
    }

    public function mobile()
    {
        return $this->hasOne('App\Models\Hr\HrContactMobile');
    }

     public function landline()
    {
        return $this->hasOne('App\Models\Hr\HrContactLandline');
    }


}
