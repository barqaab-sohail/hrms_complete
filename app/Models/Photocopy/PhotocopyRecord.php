<?php

namespace App\Models\Photocopy;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PhotocopyRecord extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $fillable = ['photocopy_id', 'remarks','date','count'];

    public function photocopy(){
        return $this->belongsTo('App\Models\Photocopy\Photocopy');
    }
}
