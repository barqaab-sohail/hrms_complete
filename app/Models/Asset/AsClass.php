<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsClass extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['name'];
}
