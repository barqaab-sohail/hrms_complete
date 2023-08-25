<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmployeeBank extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['hr_employee_id', 'bank_id', 'account_no'];

    public function bank()
    {
        return  $this->belongsTo('App\Models\Common\Bank');
    }

    public function hrEmployee()
    {
        return  $this->belongsTo('App\Models\Hr\HrEmployee');
    }
}
