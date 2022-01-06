<?php

namespace App\Models\Leave;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class LeAccumulative extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['hr_employee_id', 'le_type_id','accumulative_total','date'];

    //get value of hr_status_id and show in string as per statusOptions
    public function getLeTypeId($attribute)
    {
        return $this->statusOptions()[$attribute];
    }
    
    public function statusOptions()
    {
        return [
            1 => 'Casual Leave',
            2 => 'Annual Leave',
        ];
    }

}
