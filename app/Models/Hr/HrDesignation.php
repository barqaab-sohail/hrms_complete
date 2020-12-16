<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrDesignation extends Model implements Auditable
{
    
	use \OwenIt\Auditing\Auditable;
    protected $fillable = ['name', 'level'];
    public $timestamps = false;
   
}
