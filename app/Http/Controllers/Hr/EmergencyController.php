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
    	$input = $request->all();
        $input['hr_employee_id']=$id;

        DB::transaction(function () use ($input, $id) {  
            
        	HrEmergency::updateOrCreate(
			         	['hr_employee_id' => $id],
			         	$input);
            
    	}); // end transcation


      return response()->json(['status'=> 'OK', 'message' => 'Data Sucessfully Updated']);

    }
}