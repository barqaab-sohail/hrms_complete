<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;

class PrDocumentName extends Model
{
    

    public function prDocument()
    {
        return $this->belongsToMany('App\Models\Project\PrDocument');
    }
}
