<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Education;
use App\Models\Common\Country;
use App\Models\Hr\HrEmployee;

class EducationController extends Controller
{
    
    public function create(Request $request){

    	$degrees = Education::all();
    	$countries = Country::all();

	    if($request->ajax()){
	    	return view('hr.education.create',compact('degrees','countries'));
		}else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }
    }

    public function store(Request $request){

    		$employee = HrEmployee::find(session('hr_employee_id'));
    		$testing=false;
    		foreach ($employee->education as $edu) {
    				if ($edu->pivot->education_id == $request->input("degree_name")){
    					$testing=true;
    				}
			}

			if ($testing){
				return response()->json(['status'=> 'Not OK', 'message' => "This  Degree is already Saved"]);
			}else{

	    		$educationId = $request->input("degree_name");
	    		$countryId = $request->input("country_id");
	    		$institute = $request->input("institute");
	    		$from = $request->input("from");
	    		$to = $request->input("to");
	    		$marksObtain = $request->input("marks_obtain");
	    		$totalMarks = $request->input("total_marks");
	    		$grade = $request->input("grade");
			
				$employee->education()->attach($educationId, ['country_id'=>$countryId,'institute'=>$institute,'from'=>$from,'to'=>$to,'marks_obtain'=>$marksObtain,'total_marks'=>$totalMarks,'grade'=>$grade,]);	

				return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved"]);
			}

    }

    public function refreshTable(){

    	$employee = HrEmployee::find(session('hr_employee_id'));
       
        return view('hr.education.list',compact('employee'));
        
    }
}
