<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsPurchase extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['asset_id','as_purchase_condition_id', 'purchase_date','purchase_cost'];
}
