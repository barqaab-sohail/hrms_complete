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
use App\Models\Hr\HrMembership;
use App\Models\Common\Membership;
use App\Http\Requests\Hr\AdditionalInformationStore;
use DB;


class AdditionalInformationController extends Controller
{
    
	public function edit(Request $request, $id){

		$employee= HrEmployee::find(session('hr_employee_id'));

		$bloodGroups = BloodGroup::all();
        $memberships = Membership::all();

		if($request->ajax()){
            $view =  view('hr.additionalInformation.edit', compact('employee','bloodGroups','memberships'))->render();
            return response()->json($view);
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }

	}


	public function update(AdditionalInformationStore $request, $id){

        //ensure client end is is not changed
        if($id != session('hr_employee_id')){
            return response()->json(['status'=> 'Not OK', 'message' => "Security Breach. No Data Change "]);
        }

        
		$input = $request->all();
		if($request->filled('licence_expiry')){
          $input ['licence_expiry']= \Carbon\Carbon::parse($request->licence_expiry)->format('Y-m-d');
        }
        if($request->filled('passport_expiry')){
        $input ['passport_expiry']= \Carbon\Carbon::parse($request->passport_expiry)->format('Y-m-d');
        }

        if($request->filled('expiry')){
        $input ['expiry']= \Carbon\Carbon::parse($request->expiry)->format('Y-m-d');
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

            if($input['membership_id'] != null ){
            HrMembership::updateOrCreate(
                    ['hr_employee_id'=> session('hr_employee_id')],       //It is find and update 
                    $input);  
            }else{
                HrMembership::where('hr_employee_id', session('hr_employee_id'))->delete();
            }


        }); // end transcation

         
      	return response()->json(['status'=> 'OK', 'message' => "Data Successfully Updated"]);

	}

}
