<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeOffice extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['hr_employee_id', 'office_id', 'effective_date'];

    public function office()
    {
        return $this->belongsTo('App\Models\Common\Office');
    }
}
