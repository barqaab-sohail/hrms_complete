<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrAppointment;
use App\Models\Hr\HrSalary;
use App\Models\Hr\HrSalaryDetail;
use App\Models\Hr\HrDesignation;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDepartment;
use App\Models\Hr\HrLetterType;
use App\Models\Hr\HrCommonModel;
use App\Models\Project\PrDetail;
use DB;

class AppointmentController extends Controller
{
    public function edit(Request $request, $id){

    	$salaries = HrSalary::all();
    	$designations = HrDesignation::all();
    	$employees = HrEmployee::all();
    	$departments = HrDepartment::all();
    	$letterTypes = HrLetterType::all();
    	$projects = PrDetail::all();
    	$data = HrAppointment::where('hr_employee_id',$id)->first();

	    
	   if($request->ajax()){
	    return view('hr.appointment.edit', compact('data','salaries','designations','employees','departments','letterTypes','projects'));
	  }

    }


    public function update(Request $request, $id){
    	$input = $request->all();
            if($request->filled('joining_date')){
            $input ['joining_date']= \Carbon\Carbon::parse($request->joining_date)->format('Y-m-d');
            }
            if($request->filled('expiry_date')){
            $input ['expiry_date']= \Carbon\Carbon::parse($request->expiry_date)->format('Y-m-d');
            }
        $input['hr_employee_id']=session('hr_employee_id');
        $input['model_type']='App\Models\Hr\HrAppointment';
        

        DB::transaction(function () use ($input) {  

    		$appointmentId = HrAppointment::create($input);

            $input['model_id']=$appointmentId->id;
            $commonModelId=HrCommonModel::create($input);

            $input['hr_common_model_id']=$commonModelId->id;
            HrSalaryDetail::create($input);

    	}); // end transcation


      return response()->json(['status'=> 'OK', 'message' => 'Data Sucessfully Updated']);

    }





}
