<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsMaintenance extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['asset_id','maintenance_detail','maintenance_cost','maintenance_date'];

}
