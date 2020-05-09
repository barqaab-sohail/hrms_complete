<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrExperience;
use App\Models\Common\Country;

class ExperienceController extends Controller
{
    
    public function create(Request $request){

    	$countries = Country::all();
    	$hrExperience = HrExperience::where('hr_employee_id',session('hr_employee_id'))->get();

	    if($request->ajax()){
	    	$view =  view('hr.experience.create',compact('countries','hrExperience'))->render();
	    	return response()->json($view);

		}else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }
    }
}
