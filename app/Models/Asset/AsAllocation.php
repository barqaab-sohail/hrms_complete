<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsAllocation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['asset_id','hr_employee_id','date'];
}
