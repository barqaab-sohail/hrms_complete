<?php

namespace App\Models\Input;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class InputOfficeDepartment extends Model  implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['input_id', 'office_department_id']; 
    
}