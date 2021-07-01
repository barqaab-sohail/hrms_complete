<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsOwnership extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['asset_id','client_id','pr_detail_id', 'date'];


    public function asOwnership(){
        return $this->hasOne('App\Models\Common\Client','id','client_id');
    }

    public function asProject(){
        return $this->hasOne('App\Models\Asset\AsProject');
    }

}
