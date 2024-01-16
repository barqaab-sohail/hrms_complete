<?php

namespace App\Http\Controllers\Cv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cv\CvSpecialization;
use DB;

class SpecialityController extends Controller
{
    public function store (Request $request){

        $speciality = CvSpecialization::where('name', $request->name)->first();
      
	        if($speciality == null){
	            
	             DB::transaction(function () use ($request) {  

	                 CvSpecialization::create(['name'=>$request->name]);
	            }); // end transcation   

	            $specializations = DB::table("cv_specializations")->pluck("name","id");
	            $message = 'Degree Successfully Entered';
	        }else{
	             $specializations = '';
	             $message = "$request->name speciality is already entered";
	        }

        
        
    	return response()->json(['specializations'=> $specializations, 'message'=>$message]);
    }


}
