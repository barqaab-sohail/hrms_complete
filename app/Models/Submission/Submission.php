<?php

namespace App\Models\Submission;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Submission extends Model implements Auditable
{
    
    use \OwenIt\Auditing\Auditable;
    

   protected $fillable = ['sub_type_id','sub_division_id','project_name','client_id','submission_no'];


    protected $appends = ['client_name','division'];
    
    function getClientNameAttribute() {
        return "NTDC";
    }

    function getDivisionAttribute() {
        return "Power";
    }

   	public function date(){

        return $this->hasOne('App\Models\Submission\SubDate');

    }

    public function contact(){

        return $this->hasOne('App\Models\Submission\SubContact');

    }

    public function address(){

        return $this->hasOne('App\Models\Submission\SubAddress');

    }





   //default value of status=0
    protected $attributes = [
        'sub_type_id' => 1 //1 EOI, 2 PQD and 3 RFP 
    ];
    
    //get value of status and show in string as per statusOptions
    public function getSubTypeIdAttribute($attribute)
    {
        return $this->subTypeIdOptions()[$attribute];
    }
    
    public function subTypeIdOptions()
    {
        return [
            1 => 'EOI',
            2 => 'PQD',
            3 => 'RFP'
        ];
    }
}
