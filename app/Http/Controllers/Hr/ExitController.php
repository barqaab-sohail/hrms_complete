<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrStatus;
use App\Models\Hr\HrExit;

class ExitController extends Controller
{
    
    public function create(){

    	$hrStatuses = HrStatus::all();
    	return view ('hr.exit.create', compact('hrStatuses'));

    }


    public function store(Request $request){

            // $input = $request->all();
            // $input['hr_employee_id']=session('hr_employee_id');

            // DB::transaction(function () use ($input) {  
            //     HrExit::create($input);
            // }); // end transcation

            
            return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved"]);
    }

}
