<?php

namespace App\Models\Cv;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CvDetail extends Model implements Auditable
{
   use \OwenIt\Auditing\Auditable;
    
    protected $fillable = [
        'full_name','father_name','cnic','date_of_birth','job_starting_date','cv_submission_date','foreign_experience','donor_experience','barqaab_employment','comments',
    ];

    public function cvEducation()
    {
        return $this->belongsToMany('App\Models\Common\Education')
        ->withPivot('institute', 'passing_year')
    	->withTimestamps();
    }
    



    
    public function cvExperience()
    {
        return $this->hasMany('App\Models\Cv\CvExperience');
    }
    
     public function cvDiscipline()
    {
        return $this->hasMany('App\Models\Cv\CvDiscipline');
    }



    // public function cvSpecialization()
    // {
    //     return $this->hasManyThrough('App\Models\Cv\CvSpecialization','App\Models\Cv\CvExperience','cv_detail_id','id');
    // }

    public function cvStage()
    {
        return $this->hasMany('App\Models\Cv\CvStage');
    }
   

    public function membership()
    {
        return $this->belongsToMany('App\Models\Common\Membership')
        ->withPivot('membership_number')
        ->withTimestamps();
    }

     public function cvPhone()
    {
        return $this->hasManyThrough('App\Models\Cv\CvPhone', 'App\Models\Cv\CvContact');
    }

    public function cvContact()
    {
        return $this->hasOne('App\Models\Cv\CvContact');
    }

    public function cvReference()
    {
        return $this->hasOne('App\Models\Cv\CvReference');
    }

    public function cvSkill()
    {
        return $this->hasMany('App\Models\Cv\CvSkill');
    }

    public function cvAttachment()
    {
        return $this->hasMany('App\Models\Cv\CvAttachment');
    }


}
