<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class HrEmployee extends Model
{
    
    protected $guarded = [];


    public function user(){
        return $this->belongsTo('App\User');
    }

    public function profilePicture(){
        $picture = HrDocumentation::where([['description','Picture'],['hr_employee_id',auth()->user()->hrEmployee->id]])->first();
        if($picture){
        $profilePicture = $picture->path.$picture->file_name;
    	}else{
    		$profilePicture='';
    	}
        return $profilePicture;
    }


}
