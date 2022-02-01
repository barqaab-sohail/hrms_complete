<?php

namespace App\Models\Leave;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class LeSanctioned extends Model  implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['leave_id', 'manager_id','le_status_type_id','remarks']; 
}
