<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Asset extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['description','asset_code','as_sub_class_id'];


    public function asCondition(){

            return $this->hasOneThrough('App\Models\Asset\AsConditionType', 'App\Models\Asset\AsCondition',
            	'asset_id',
            	'id',
            	'id',
            	'as_condition_type_id'
            	);


    }

    public function asPurchaseCondition(){

        return $this->hasOneThrough('App\Models\Asset\AsPurchaseCondition', 'App\Models\Asset\AsPurchase',
            'asset_id',
            'id',
            'id',
            'as_purchase_condition_id'
            );
    }

    public function asOwnership(){

        return $this->hasOneThrough('App\Models\Common\Client', 'App\Models\Asset\AsOwnership',
            'asset_id',
            'id',
            'id',
            'client_id'
            );
    }



    public function asClass(){
        return $this->hasOne('App\Models\Asset\AsSubClass','id','as_sub_class_id');
    }

    //Location at the time of Purhcase
    public function asLocationFirst(){

        return $this->hasOne('App\Models\Asset\AsLocation')->where('date',$this->asPurchase->purchase_date);
    }

    //Allocation at the time of Purhcase
    public function asAllocationFirst(){

        return $this->hasOne('App\Models\Asset\AsAllocation')->where('date',$this->asPurchase->purchase_date);
    }


    public function asSubClass(){
        return $this->belongsTo('App\Models\Asset\AsSubClass');
    }

    public function asPurchase(){
        return $this->hasOne('App\Models\Asset\AsPurchase');
    }

    
    public function asDocumentation(){
        return $this->hasOne('App\Models\Asset\AsDocumentation');
    }

}
