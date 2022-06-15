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

    //Current Location of Asset
    // public function asCurrentLocation(){

    //     return $this->hasOne('App\Models\Asset\AsLocation')->orderby('date', 'desc');

    // }

    public function asCurrentLocation(){
        return $this->hasOneThrough(
            'App\Models\Office\Office',
            'App\Models\Asset\AsLocation',
            'asset_id',
            'id',
            'id',
            'office_id'
            )->orderby('date', 'desc');
    }

    public function asCurrentAllocation(){
        return $this->hasOneThrough(
            'App\Models\Hr\HrEmployee',
            'App\Models\Asset\AsLocation',
            'asset_id',
            'id',
            'id',
            'hr_employee_id'
            )->orderby('date', 'desc');
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

    

    //Allocation at the time of Purhcase
    public function asAllocationFirst(){

        return $this->hasOne('App\Models\Asset\AsAllocation');
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

    public function asPicture(){
        return $this->hasOne('App\Models\Asset\AsDocumentation')->where('description','image');
    }


    public function asLocation(){

        return $this->hasMany('App\Models\Asset\AsLocation');
    }

}
