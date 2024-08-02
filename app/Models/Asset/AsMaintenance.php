<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsMaintenance extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['asset_id','maintenance_detail','maintenance_cost','maintenance_date'];

    protected $appends = ['formatted_date','formatted_cost'];

    public function getFormattedDateAttribute()
    {
        return \Carbon\Carbon::parse($this->maintenance_date)->format('M d, Y');
    }

    public function getFormattedCostAttribute()
    {
        return number_format($this->maintenance_cost,0);
    }


}
