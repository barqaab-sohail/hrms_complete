<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class HrDocumentName extends Model
{
    

    public function hrDocumentation()
    {
        return $this->belongsToMany('App\Models\Hr\HrDocumentation');
    }

}
