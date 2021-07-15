<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsSubClass extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['name','as_class_id'];

    
}
