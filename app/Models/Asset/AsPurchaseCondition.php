<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsPurchaseCondition extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
   
}
