<?php

namespace App\Http\Controllers\Cv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Education;
use DB;

class EducationController extends Controller
{
    
     public function store (Request $request){

        $degree = Education::where('degree_name', $request->degree_name)->first();
      
	        if($degree == null){
	            
	             DB::transaction(function () use ($request) {  

	                 Education::create(['degree_name'=>$request->degree_name, 'level'=>$request->level]);
	            }); // end transcation   

	            $degrees = DB::table("educations")->pluck("degree_name","id");
	            $message = 'Degree Successfully Entered';
	        }else{
	             $degrees = '';
	             $message = "$request->degree_name is already entered";
	        }

        
        
    	return response()->json(['degrees'=> $degrees, 'message'=>$message]);
    }
}
 								