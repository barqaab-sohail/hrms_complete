<?php

namespace App\Models\Office;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class OfficeDepartment extends Model  implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['office_id', 'name']; 
    
}