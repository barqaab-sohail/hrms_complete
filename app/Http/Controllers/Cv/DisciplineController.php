<?php

namespace App\Http\Controllers\Cv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cv\CvDiscipline;
use DB;

class DisciplineController extends Controller
{
    
    public function store (Request $request){

        $discipline = CvDiscipline::where('name', $request->name)->first();
      
	        if($discipline == null){
	            
	             DB::transaction(function () use ($request) {  

	                 CvDiscipline::create(['name'=>$request->name]);
	            }); // end transcation   

	            $disciplines = DB::table("cv_disciplines")->pluck("name","id");
	            $message = 'Discipline Successfully Entered';
	        }else{
	             $disciplines = '';
	             $message = "$request->name discipline is already entered";
	        }

        
        
    	return response()->json(['disciplines'=> $disciplines, 'message'=>$message]);
    }

}
