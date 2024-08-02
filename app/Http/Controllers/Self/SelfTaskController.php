<?php

namespace App\Http\Controllers\Self;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Hr\HrEmployee;
use App\Http\Requests\Self\TaskStore;
use App\Models\Self\SsTask;
use DB;


class SelfTaskController extends Controller
{
   
    public function index (){
    	$employee = HrEmployee::where('user_id', Auth::user()->id)->first();
    	$tasks = SsTask::where('hr_employee_id',  $employee->id)->get();
    	return view('self.task.list',compact('tasks'));
    }


    public function store(TaskStore $request){

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

    	return response()->json(['status'=> 'OK', 'message' => " Data Successfully Saved"]);
    }

    public function edit(Request $request, $id){

        // $employee = HrEmployee::where('user_id', Auth::user()->id)->first();
        // $tasks = SsTask::where('hr_employee_id',  $employee->id)->get();
        $data = SsTask::find($id);
        
        if($request->ajax()){
        
            // $view =  view('self.task.editModal',compact('tasks','data'))->render();
            // return response()->json($view);
            return response()->json(['status'=> 'OK', 'data'=>$data]);
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }

    }

    public function update(TaskStore $request, $id){
            $input = $request->all();
            if($request->filled('completion_date')){
            $input ['completion_date']= \Carbon\Carbon::parse($request->completion_date)->format('Y-m-d');
            }
            if($request->filled('target_completion')){
            $input ['target_completion']= \Carbon\Carbon::parse($request->target_completion)->format('Y-m-d');
            }

             SsTask::findOrFail($id)->update($input);

            return response()->json(['status'=> 'OK', 'message' => "Data Successfully Updated"]);


    }

    public function updateStatus($id){

        $taskStatus = SsTask::find($id);
                
        if($taskStatus->status === "Pending"){
            $taskStatus->update(['status'=>1]);
        }else{
             $taskStatus->update(['status'=>0]);
        }

        return response()->json(['status'=> 'OK', 'message' => "Status Successfully Changed"]);
    }



   public function destroy($id){
        
        SsTask::findOrFail($id)->delete(); 

        return response()->json(['status'=> 'OK', 'message' => 'Data Successfully Deleted']);
    }
}
