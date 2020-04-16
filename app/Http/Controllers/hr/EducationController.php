<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    
    public function create(Request $request){

	    if($request->ajax()){
	    	return view('hr.education.create');
		}else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }
    }
}
