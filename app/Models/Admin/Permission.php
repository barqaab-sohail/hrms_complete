<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Permission extends Model implements Auditable
{
   use \OwenIt\Auditing\Auditable;
   
    protected $fillable = ['name', 'guard_name'];

}
