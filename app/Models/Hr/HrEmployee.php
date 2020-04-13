<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class HrEmployee extends Model
{
    
    protected $guarded = [];


    public function user(){
        return $this->belongsTo('App\User');
    }


}
