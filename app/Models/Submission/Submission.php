<?php

namespace App\Models\Submission;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Common\Client;
use App\Models\Project\PrDivision;

class Submission extends Model implements Auditable
{
    
    use \OwenIt\Auditing\Auditable;
    

   protected $fillable = ['sub_type_id','sub_division_id','project_name','client_id','submission_no','comments'];


    public function getClientIdAttribute($value) {
      
        return Client::find($value)->name;
    }


    function getSubDivisionIdAttribute($value) {
        return PrDivision::find($value)->name;
    }

    public function subStatusType(){

        return $this->hasOneThrough('App\Models\Submission\SubStatus', 'App\Models\Submission\SubDescription',
            'submission_id',
            'id',
            'id',
            'sub_status_id'
            );
    }

    public function subFinancialType(){

        return $this->hasOneThrough('App\Models\Submission\SubFinancialType', 'App\Models\Submission\SubDescription',
            'submission_id',
            'id',
            'id',
            'sub_financial_type_id'
            );
    }

    public function SubCvFormat(){

        return $this->hasOneThrough('App\Models\Submission\SubCvFormat', 'App\Models\Submission\SubDescription',
            'submission_id',
            'id',
            'id',
            'sub_cv_format_id'
            );
    }
    public function subDescription(){
        return $this->hasOne('App\Models\Submission\SubDescription');
    }

    public function subType(){
        return $this->belongsTo('App\Models\Submission\SubType');
    }

    public function subEoiReference(){

        return $this->hasOne('App\Models\Submission\SubEoiReference');

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
    // public function getSubTypeIdAttribute($attribute)
    // {
    //     return $this->subTypeIdOptions()[$attribute];
    // }
    
    // public function subTypeIdOptions()
    // {
    //     return [
    //         1 => 'EOI',
    //         2 => 'PQD',
    //         3 => 'RFP'
    //     ];
    // }
}
