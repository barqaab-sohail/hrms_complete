<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AsDocumentation extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['asset_id','description','file_name','extension','path','size'];


    public function asset(){
        return $this->hasOne('App\Models\Asset\Asset');
    }

}

