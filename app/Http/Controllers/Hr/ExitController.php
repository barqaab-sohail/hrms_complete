<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrStatus;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrExit;
use DB;

class ExitController extends Controller
{
    
    public function create(){
    	$hrExits =  HrExit::where('hr_employee_id', session('hr_employee_id'))->get();
    	$employee = HrEmployee::find(session('hr_employee_id'));
    	$hrStatuses = HrStatus::all();
    	return view ('hr.exit.create', compact('hrStatuses','hrExits','employee'));

    }


    public function store(Request $request){

            $input = $request->all();
            $input['hr_employee_id']=session('hr_employee_id');

            if($request->filled('effective_date')){
            $input ['effective_date']= \Carbon\Carbon::parse($request->effective_date)->format('Y-m-d');
            }
            
            DB::transaction(function () use ($input) {  
                
                HrExit::create($input);
                HrEmployee::findOrFail(session('hr_employee_id'))->update(['hr_status_id' => $input['hr_status_id']]);


            }); // end transcation

            
            return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved"]);
    }

    public function edit (Request $request, $id){
    	//For security checking
        session()->put('exit_edit_id', $id);

        $data = HrExit::find($id);
    	$hrStatuses = HrStatus::all();
    	$hrExits =  HrExit::where('hr_employee_id', session('hr_employee_id'))->get();    	
    	
        if($request->ajax()){
            return view('hr.exit.edit', compact('data','hrStatuses','hrExits'));
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }

    }

     public function update (Request $request, $id){
    	 //ensure client end id is not changed
        if($id != session('exit_edit_id')){
            return response()->json(['status'=> 'Not OK', 'message' => "Security Breach. No Data Change "]);
        }

        $input = $request->all();
    	 if($request->filled('effective_date')){
            $input ['effective_date']= \Carbon\Carbon::parse($request->effective_date)->format('Y-m-d');
            }

    	DB::transaction(function () use ($input, $id) {  

    		HrExit::findOrFail($id)->update($input);
    		HrEmployee::findOrFail(session('hr_employee_id'))->update(['hr_status_id' => $input['hr_status_id']]);
    	
    	}); // end transcation

    	
    	return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Updated"]);
    }

     public function destroy($id){
        
        if(!in_array($id, session('exit_delete_ids'))){
            return response()->json(['status'=> 'Not OK', 'message' => "Security Breach. No Data Change "]);
        }
    	
        DB::transaction(function () use ($id) {  

    		HrExit::find($id)->delete();
    		   	
    	}); // end transcation
        

       
        return response()->json(['status'=> 'OK', 'message' => 'Data Sucessfully Deleted']);
    }


    public function refreshTable(){
        $hrExits =  HrExit::where('hr_employee_id', session('hr_employee_id'))->get();

        $ids = $hrExits->pluck('id')->toArray();
        //For security checking
        session()->put('exit_delete_ids', $ids);

        return view('hr.exit.list',compact('hrExits'));
    }

}
