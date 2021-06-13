<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsOwnership extends Model implements Auditable
{
     use \OwenIt\Auditing\Auditable;
    protected $fillable = ['asset_id','client_id', 'date'];
}
