<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Client extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
	public $timestamps = false;

    protected $fillable = ['name'];
}
