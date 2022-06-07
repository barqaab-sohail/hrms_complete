<?php

namespace App\Models\Submission;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SubCompetitor extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    
   	protected $fillable = ['submission_id', 'name'];

   	public function subTechnicalScore(){
    	return $this->hasOne('App\Models\Submission\SubTechnicalScore');
    }

    public function subResult(){
    	return $this->hasOne('App\Models\Submission\SubResult');
    }

    public function subFinancialScore(){
    	return $this->hasMany('App\Models\Submission\SubFinancialScore');
    }

}
