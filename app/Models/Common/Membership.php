<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class Membership extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
    //
}
