<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\BloodGroup;
use App\Models\Hr\HrBloodGroup;
use App\Models\Hr\HrPassport;
use App\Models\Hr\HrDriving;
use App\Models\Hr\HrDisability;
use App\Models\Hr\HrEmployee;
use DB;


class AdditionalInformationController extends Controller
{
    
	public function edit(Request $request, $id){

		$employee= HrEmployee::find(session('hr_employee_id'));

		$bloodGroups = BloodGroup::all();

		if($request->ajax()){
            $view =  view('hr.additionalInformation.edit', compact('employee','bloodGroups'))->render();
            return response()->json($view);
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }

	}


	public function update(Request $request, $id){

		$input = $request->all();
		if($request->filled('licence_expiry')){
          $input ['licence_expiry']= \Carbon\Carbon::parse($request->licence_expiry)->format('Y-m-d');
        }
        if($request->filled('passport_expiry')){
        $input ['passport_expiry']= \Carbon\Carbon::parse($request->passport_expiry)->format('Y-m-d');
        }
        $input['hr_employee_id'] = session('hr_employee_id');
       
        DB::transaction(function () use ($input) {  


        	if($input['licence_no'] != null ){
        	HrDriving::updateOrCreate(
                    ['hr_employee_id'=> session('hr_employee_id')],       //It is find and update 
                    $input);  
        	}else{
        		HrDriving::where('hr_employee_id', session('hr_employee_id'))->delete();
        	}

        	if($input['blood_group_id'] != null ){
        	HrBloodGroup::updateOrCreate(
                    ['hr_employee_id'=> session('hr_employee_id')],       //It is find and update 
                    $input);  
        	}else{
        		HrBloodGroup::where('hr_employee_id', session('hr_employee_id'))->delete();
        	}

        	if($input['passport_no'] != null ){
        	HrPassport::updateOrCreate(
                    ['hr_employee_id'=> session('hr_employee_id')],       //It is find and update 
                    $input);  
        	}else{
        		HrPassport::where('hr_employee_id', session('hr_employee_id'))->delete();
        	}

        	if($input['detail'] != null ){
        	HrDisability::updateOrCreate(
                    ['hr_employee_id'=> session('hr_employee_id')],       //It is find and update 
                    $input);  
        	}else{
        		HrDisability::where('hr_employee_id', session('hr_employee_id'))->delete();
        	}


        }); // end transcation

         $input = json_encode($input['licence_no']);
      	return response()->json(['status'=> 'OK', 'message' => "$input Data Sucessfully Updated"]);

	}

}
