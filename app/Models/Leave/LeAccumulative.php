<?php

namespace App\Models\Leave;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class LeAccumulative extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $fillable = ['hr_employee_id', 'le_type_id','accumulative_total','date'];

    public function leType(){
        return $this->belongsTo('App\Models\Leave\LeType');
    }

}
