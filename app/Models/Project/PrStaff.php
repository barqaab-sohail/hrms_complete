<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrStaff extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'pr_staffs';

    protected $fillable = ['pr_detail_id', 'hr_employee_id', 'position', 'from', 'to', 'working_as', 'status'];

    public function getFormattedFromAttribute()
    {
        return \Carbon\Carbon::parse($this->from ?? '')->format('M d, Y');
    }
    public function getFormattedToAttribute()
    {
        if ($this->to) {
            return \Carbon\Carbon::parse($this->to)->format('M d, Y');
        } else {
            return null;
        }
    }


    public function hrEmployee()
    {
        return $this->belongsTo('App\Models\Hr\HrEmployee');
    }
}
