<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class Partner extends Model implements Auditable
{ 
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['name'];
}
