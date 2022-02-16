<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrPosition extends Model implements Auditable
{
   use \OwenIt\Auditing\Auditable;

    protected $fillable = ['hr_designation_id', 'total_mm','pr_position_type_id','pr_detail_id'];

    public function hrDesignation(){
            return $this->belongsTo('App\Models\Hr\HrDesignation');
    }

     public function prPositionType(){
            return $this->belongsTo('App\Models\Project\PrPositionType');
    }

}
