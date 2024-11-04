<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HrEmployeeCompany extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['hr_employee_id', 'partner_id', 'effective_date', 'end_date', 'status'];

    public function partner()
    {
        return $this->belongsTo('App\Models\Common\Partner');
    }

    public function hrEmployee()
    {
        return $this->belongsTo('App\Models\Hr\HrEmployee');
    }
}
