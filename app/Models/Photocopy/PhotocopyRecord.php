<?php

namespace App\Models\Photocopy;

use Illuminate\Database\Eloquent\Model;

class PhotocopyRecord extends Model
{
    use \OwenIt\Auditing\Auditable;
    
    protected $fillable = ['photocopy_id', 'remarks','date'];

    public function photocopy(){
        return $this->belongsTo('App\Models\Photocopy\Photocopy');
    }
}
