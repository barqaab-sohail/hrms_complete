<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsConsumable extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['asset_id','consumable_id','unit_id','consumable_cost','consumable_qty','consumable_date'];
    protected $appends = ['formatted_date','formatted_cost','consumable_detail','consumable_unit'];

    public function getFormattedDateAttribute()
    {
        return \Carbon\Carbon::parse($this->consumable_date)->format('M d, Y');
    }

    public function getFormattedCostAttribute()
    {
        return number_format($this->consumable_cost,0);
    }
    
    public function getConsumableDetailAttribute()
    {
        return $this->consumable?->name;
    }

    public function getConsumableUnitAttribute()
    {
        return $this->Unit?->name;
    }
    //belong to Consumable Items
    public function consumable(){
        return $this->belongsTo('App\Models\Asset\Consumable');
    }

    public function unit(){
        return $this->belongsTo('App\Models\Asset\Unit');
    }

  
}
