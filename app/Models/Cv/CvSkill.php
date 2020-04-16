<?php

namespace App\Models\Cv;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CvSkill extends Model implements Auditable
{
    
    use \OwenIt\Auditing\Auditable;
    
    protected $fillable = [
        'skill_name', 'cv_detail_id',
    ];
}
