<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrPostingProject extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['hr_posting_id', 'pr_detail_id'];

    public function project(){
        return $this->belongsTo('App\Models\Project\PrDetail');
    }
}
