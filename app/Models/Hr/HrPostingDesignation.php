<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrPostingDesignation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['hr_posting_id','hr_designation_id'];

    public function hrDesignation(){
        return $this->belongsTo('App\Models\Hr\HrDesignation');
    }
}
