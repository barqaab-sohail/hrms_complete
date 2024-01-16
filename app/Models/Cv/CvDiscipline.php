<?php

namespace App\Models\Cv;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class CvDiscipline extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
    
     protected $fillable = [
        'name', 
    ];

}
