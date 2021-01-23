<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrManager;
use App\Models\Hr\HrEmployee;
use DB;
use DataTables;

class ManagerController extends Controller
{
    
    public function create() {
    	
       	$employees = HrEmployee::where('hr_status_id',1)->get();
       	$managers = HrManager::where('hr_employee_id',session('hr_employee_id'))->get();
        
        $view =  view('hr.manager.managerModal', compact('employees','managers'))->render();
        return response()->json($view);

    }

    public function store (Request $request){

    	$input = $request->all();
    	if($request->filled('effective_date')){
            $input ['effective_date']= \Carbon\Carbon::parse($request->effective_date)->format('Y-m-d');
        }

         DB::transaction(function () use ($input) {  
        	HrManager::create($input);
        }); // end transcation      
   
       return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Update"]);

    }

    public function edit(Request $request, $id){
    	$data =  HrManager::where('id',$id)->get();

    	return response()->json(['data'=>$data]);
    }
    
    public function destroy ($id){

    	DB::transaction(function () use ($id) {  
    		HrManager::find($id)->delete();  		   	
    	}); // end transcation
         
        return response()->json(['status'=> 'OK', 'message' => 'Data Sucessfully Deleted']);

    }


}
