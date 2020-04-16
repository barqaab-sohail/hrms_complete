<?php

namespace App\Http\Controllers\hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Gender;
use App\Models\Common\MaritalStatus;
use App\Models\Common\Religion;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrStatus;
use DB;
use App\Http\Requests\Hr\EmployeeStore;


class EmployeeController extends Controller
{
    public function create(){
        session()->put('hr_employee_id', '');
    	$genders = Gender::all();
    	$maritalStatuses = MaritalStatus::all();
    	$religions = Religion::all();
        
    	return view ('hr.employee.create', compact('genders','maritalStatuses','religions'));
    }


    public function store (EmployeeStore $request){
       
    	 $input = $request->all();
            if($request->filled('date_of_birth')){
            $input ['date_of_birth']= \Carbon\Carbon::parse($request->date_of_birth)->format('Y-m-d');
            }
            if($request->filled('cnic_expiry')){
            $input ['cnic_expiry']= \Carbon\Carbon::parse($request->cnic_expiry)->format('Y-m-d');
            }
            
            $input ['hr_status_id']=HrStatus::where('name','On Board')->first()->id;

            $employee='';
    	DB::transaction(function () use ($input, &$employee) {  

    		$employee = HrEmployee::create($input);

    	}); // end transcation

        //return response()->json(['url'=>url('/dashboard')]);
        
    	return response()->json(['url'=> route("employee.edit",$employee),'message' => 'Data Sucessfully Saved']);
    }

    public function index(){
    	$employees = HrEmployee::all();
    	return view ('hr.employee.list',compact('employees'));
    }


    public function edit(Request $request, $id){
    	$genders = Gender::all();
    	$maritalStatuses = MaritalStatus::all();
    	$religions = Religion::all();
    	$data = HrEmployee::find($id);
        session()->put('hr_employee_id', $data->id);
      
        if($request->ajax()){      
            return view ('hr.employee.ajax', compact('genders','maritalStatuses','religions','data'));    
        }else{
            return view ('hr.employee.edit', compact('genders','maritalStatuses','religions','data'));       
        }
    }


    public function update(EmployeeStore $request, $id){

    	$input = $request->all();
            if($request->filled('date_of_birth')){
            $input ['date_of_birth']= \Carbon\Carbon::parse($request->date_of_birth)->format('Y-m-d');
            }
            if($request->filled('cnic_expiry')){
            $input ['cnic_expiry']= \Carbon\Carbon::parse($request->cnic_expiry)->format('Y-m-d');
            }

    		DB::transaction(function () use ($input, $id) {  

    		HrEmployee::findOrFail($id)->update($input);

    		}); // end transcation
        
        if($request->ajax()){
    	return response()->json(['status'=> 'OK', 'message' => 'Data Sucessfully Updated']);
        }else{
            return back()->with('message', 'Data Sucessfully Updated');
        }
    }

    public function destroy($id)
    {   
        HrEmployee::findOrFail($id)->delete();

       return back()->with('message', 'Data Sucessfully Deleted');
        //return response()->json(['status'=> 'OK', 'message' => 'Data Sucessfully Deleted']);
    }

}
