<?php

namespace App\Models\Cv;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class CvStage extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
    
    protected $fillable = [
        'name', 
    ];
}
