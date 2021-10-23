<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PrDirectCost extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['direct_cost'];
}
