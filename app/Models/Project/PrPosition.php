<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrPosition extends Model implements Auditable
{
   use \OwenIt\Auditing\Auditable;

     protected $fillable = ['name', 'total_mm','pr_position_type_id','pr_detail_id'];
}
