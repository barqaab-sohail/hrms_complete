<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrDesignation;
use App\Models\Hr\HrDepartment;
use App\Models\Project\PrDetail;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrSalary;
use App\Models\Office\Office;
use App\Models\Hr\HrPosting;
use DB;

class PostingController extends Controller
{
    
    public function create (Request $request){

    	$designations = HrDesignation::all();
    	$departments = HrDepartment::all();
    	$projects = PrDetail::all();
    	$managers = HrEmployee::all();
    	$salaries = HrSalary::all();
    	$offices = Office::all();
    	$hrPostings = HrPosting::where('hr_employee_id',session('hr_employee_id'))->get();
       

		   if($request->ajax()){
	             $view =  view('hr.posting.create', compact('designations','departments','projects','managers','salaries','offices','hrPostings'))->render();

            	return response()->json($view);

	        }else{
	            return back()->withError('Please contact to administrator, SSE_JS');
	        }
    }

    public function store (Request $request){
    		$input = $request->all();

    		if($request->filled('effective_date')){
            $input ['effective_date']= \Carbon\Carbon::parse($request->effective_date)->format('Y-m-d');
            }
    		
    	DB::transaction(function () use ($request,$input) { 

            $input['hr_employee_id'] = session('hr_employee_id');

            HrPosting::create($input);

    	});  //end transaction

    	return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved"]);

    }

    public function edit (Request $request, $id){

    	$designations = HrDesignation::all();
    	$departments = HrDepartment::all();
    	$projects = PrDetail::all();
    	$managers = HrEmployee::all();
    	$salaries = HrSalary::all();
    	$offices = Office::all();
    	$data = HrPosting::find($id);

    	$hrPostings = HrPosting::where('hr_employee_id',session('hr_employee_id'))->get();
       

		   if($request->ajax()){
	             $view =  view('hr.posting.edit', compact('designations','departments','projects','managers','salaries','offices','hrPostings','data'))->render();

            	return response()->json($view);

	        }else{
	            return back()->withError('Please contact to administrator, SSE_JS');
	        }
    }

    public function update (Request $request, $id){
    		$input = $request->all();

    		if($request->filled('effective_date')){
            $input ['effective_date']= \Carbon\Carbon::parse($request->effective_date)->format('Y-m-d');
            }
    		
    	DB::transaction(function () use ($id,$input) { 

           HrPosting::findOrFail($id)->update($input);
           
    	});  //end transaction

    	return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved"]);

    }



    public function destroy ($id){

    	 DB::transaction(function () use ($id) {  

    	 	$hrPosting = HrPosting::find($id);
    	  	$hrPosting->delete();
    		
    	 }); // end transcation


        return response()->json(['status'=> 'OK', 'message' => 'Data Sucessfully Deleted']);

    }

     public function refreshTable(){

    	$hrPostings = HrPosting::where('hr_employee_id',session('hr_employee_id'))->get();
       
        return view('hr.posting.list',compact('hrPostings'));
        
    }
}
