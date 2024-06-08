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

    
    //belong to Consumable Items
    public function consumable(){
        return $this->belongsTo('App\Models\Asset\Consumable');
    }

    public function unit(){
        return $this->belongsTo('App\Models\Asset\Unit');
    }
}
