<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave\Leave;
use App\Models\Leave\LeStatusType;
use App\Models\Leave\LeSanctioned;
use App\Models\Hr\HrEmployee;
use DB;

class LeaveStatusController extends Controller
{
    

    // public function index(){

    // 	$leStatusTypes = LeStatusType::all();

    // 	return response()->json($leStatusTypes);

    // }

    public function edit($id)
    {
        
        $leave = Leave::find($id);
        $employee = HrEmployee::find($leave->hr_employee_id);
        $manager = '';
        
        if($leave->leSanctioned){
        	$manager = HrEmployee::with('employeeDesignation')->find($leave->leSanctioned->manager_id);
    	}else{
    		$manager =  HrEmployee::with('employeeDesignation')->find($employee->hod->hr_manager_id??'');
    	}

        return response()->json(['leaveStatus'=>$leave->leSanctioned??'', 'manager'=>$manager]);
    }

    public function store (Request $request){

    	$input = $request->all();
    	
        
         DB::transaction(function () use ($input, $request) {  
        	
      		if($request->filled('le_status_type_id')){
	            LeSanctioned::updateOrCreate(['leave_id' => $input['leave_id']],
	                ['le_status_type_id'=> $input['le_status_type_id'],
	                'leave_id'=> $input['leave_id'],
	                'remarks'=> $input['remarks'],
	                'manager_id'=> $input['manager_id']]); 
	        }else{
	        	LeSanctioned::where('leave_id',$request->leave_id)->delete();
	        }

            //$data = HrManager::create($input);
        }); // end transcation   

       return response()->json(['success'=>'Data saved successfully.']);

    }
}
