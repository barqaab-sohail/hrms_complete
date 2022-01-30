<?php

namespace App\Models\Leave;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class LeHalfDay extends Model  implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['leave_id', 'time','description']; 
}
