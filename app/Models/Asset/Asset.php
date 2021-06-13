<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Asset extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['description','asset_code','as_sub_class_id'];

}
