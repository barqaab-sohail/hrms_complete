<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;

class HrAlertController extends Controller
{
    
    public function alertList(){
    	$nextTenDays = \Carbon\Carbon::now()->addDays(10)->format('Y-m-d');
    	$totalCnicExpire = HrEmployee::where('hr_status_id',1)->where('cnic_expiry','<',$nextTenDays)->count();

    	$appointmentExpiryTotal = appointmentExpiryTotal();
    	
    	return view('hr.alert.list',compact('totalCnicExpire','appointmentExpiryTotal'));
	}

	public function cnicExpiryDetail(){
		$nextTenDays = \Carbon\Carbon::now()->addDays(10)->format('Y-m-d');
		$employees = HrEmployee::where('hr_status_id',1)->where('cnic_expiry','<',$nextTenDays)->get();

      	foreach ($employees as $employee){
    		$data [] = array(
				"employee_name" => employeeFullName($employee->id).' - '.$employee->employee_no,
				"cnic_expiry_date" => $employee->cnic_expiry,
			);  			
    	}

    	usort($data, function($a, $b) {
    		return strtotime($a['cnic_expiry_date']) - strtotime($b['cnic_expiry_date']);
		});

    	return response()->json(['status'=> 'Ok', 'full_name'=>'CNIC Expiry Detail', 'cnicExpiry'=>$data]);
	}

	public function appointmentExpiry(){
		$nextTenDays = \Carbon\Carbon::now()->addDays(10)->format('Y-m-d');
		$employees = HrEmployee::where('hr_status_id',1)->with('employeeAppointment','employeeProject')->get();

		foreach ($employees as $key => $employee) {                   
            if($employee->employeeAppointment->expiry_date??''!=''){
                if($employee->employeeAppointment->expiry_date<$nextTenDays){
            		$data [] = array(
					"employee_name" => employeeFullName($employee->id).' - '.$employee->employee_no,
                    "employee_project"=>$employee->employeeProject->last()->name??'',
					"appointment_expiry_date" => $employee->employeeAppointment->expiry_date,
					);  	
                    
                }
            }
            
        }

    	usort($data, function($a, $b) {
    		return strtotime($a['appointment_expiry_date']) - strtotime($b['appointment_expiry_date']);
		});

    	return response()->json(['status'=> 'Ok', 'full_name'=>'Appointment Expiry Detail', 'appointmentExpiry'=>$data]);
	}
}
