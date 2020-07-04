<?php

namespace App\Http\Controllers\Self;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Hr\HrEmployee;
use App\Models\Self\SsTask;
use DB;


class SelfTaskController extends Controller
{
   
    public function index (){
    	$employee = HrEmployee::where('user_id', Auth::user()->id)->first();
    	$tasks = SsTask::where('hr_employee_id',  3)->get();
    	return view('self.task.list',compact('tasks'));
    }


    public function store(Request $request){

    	$input = $request->all();
    	if($request->filled('completion_date')){
            $input ['completion_date']= \Carbon\Carbon::parse($request->completion_date)->format('Y-m-d');
            }
        if($request->filled('target_completion')){
            $input ['target_completion']= \Carbon\Carbon::parse($request->target_completion)->format('Y-m-d');
            }
    	$employee = HrEmployee::where('user_id', Auth::user()->id)->first();
        $input['hr_employee_id']= $employee->id;
      
        DB::transaction(function () use ($input) {  
            SsTask::create($input);
        }); // end transcation

    	return response()->json(['status'=> 'OK', 'message' => " Data Sucessfully Saved"]);
    }
}
