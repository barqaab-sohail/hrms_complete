<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrNextToKin;
use App\Http\Requests\Hr\EmergencyStore;
use DB;

class NextToKinController extends Controller
{
     public function edit(Request $request, $id){

    	$data = HrNextToKin::where('hr_employee_id',$id)->first();


    	return view ('hr.nextToKin.edit',compact('data'));

    }

    public function update(EmergencyStore $request, $id){
    	$input = $request->all();
        $input['hr_employee_id']=$id;

        DB::transaction(function () use ($input, $id) {  
            
        	HrNextToKin::updateOrCreate(
			         	['hr_employee_id' => $id],
			         	$input);
            
    	}); // end transcation


      return response()->json(['status'=> 'OK', 'message' => 'Data Sucessfully Updated']);

    }
}
