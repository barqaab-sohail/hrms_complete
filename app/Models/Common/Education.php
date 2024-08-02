<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class Education extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'educations';

    protected $fillable = ['degree_name', 'level'];
}
