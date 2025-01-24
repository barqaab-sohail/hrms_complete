<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class HrEducation extends Model implements Auditable
{
	use \OwenIt\Auditing\Auditable;
    protected $table = 'hr_educations';

     protected $fillable = ['hr_employee_id','education_id','country_id','institute','major','from','to','total_marks','marks_obtain','grade'];


    public function education(){

        return $this->belongsTo('App\Models\Common\Education');

    }

}
