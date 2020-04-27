<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Education;

class EducationController extends Controller
{
    
    public function create(Request $request){

    	$degrees = Education::all();

	    if($request->ajax()){
	    	return view('hr.education.create',compact('degrees'));
		}else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }
    }
}
