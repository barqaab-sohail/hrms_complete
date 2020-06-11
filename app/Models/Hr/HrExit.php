<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class HrExit extends Model implements Auditable
{ 
    use \OwenIt\Auditing\Auditable;

}
