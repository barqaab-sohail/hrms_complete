<?php

namespace App\Models\Submission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SubPosition extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    
   protected $fillable = ['submission_id','position'];

   	public function subManMonth(){
        return $this->hasOne('App\Models\Submission\SubManMonth');
    }

    public function subNominatePerson(){
        return $this->hasOne('App\Models\Submission\SubNominatePerson');
    }

    public function subCv(){
        return $this->hasOne('App\Models\Submission\SubCv');
    }
}
