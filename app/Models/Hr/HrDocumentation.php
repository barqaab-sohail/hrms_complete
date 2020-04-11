<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class HrDocumentation extends Model
{
     protected $fillable = ['hr_employee_id','description','file_name','size','path','extension','content'];



      public function hrDocumentName()
    {
        return $this->belongsToMany('App\Models\Hr\HrDocumentName');
        
    }
}
