<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrPostingSalary extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['hr_posting_id', 'hr_salary_id'];

    public function hrSalary(){
        return $this->belongsTo('App\Models\Hr\HrSalary');
    }
}
