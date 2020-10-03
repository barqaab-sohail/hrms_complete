<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrPostingManager extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['hr_posting_id','hr_manager_id'];

    public function hrManager(){
        return $this->belongsTo('App\Models\Hr\HrEmployee');
    }
}
