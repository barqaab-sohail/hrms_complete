<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmergency;
use App\Http\Requests\Hr\EmergencyStore;
use DB;

class EmergencyController extends Controller
{
    public function edit(Request $request, $id){

    	$data = HrEmergency::where('hr_employee_id',$id)->first();


    	return view ('hr.emergency.edit',compact('data'));

    }

    public function update(EmergencyStore $request, $id){
        //ensure client end id is not changed
        if($id != session('hr_employee_id')){
            return response()->json(['status'=> 'Not OK', 'message' => "Security Breach. No Data Change "]);
        }
        
    	$input = $request->all();
        $input['hr_employee_id']=session('hr_employee_id');

        DB::transaction(function () use ($input) {  
            
        	HrEmergency::updateOrCreate(
			         	['hr_employee_id' => session('hr_employee_id')],
			         	$input);
            
    	}); // end transcation


      return response()->json(['status'=> 'OK', 'message' => 'Data Successfully Updated']);

    }
}
