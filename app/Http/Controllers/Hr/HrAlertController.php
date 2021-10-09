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
        $drivingLicenceExpiryTotal = drivingLicenceExpiryTotal();
        $pecCardExpiryTotal = pecCardExpiryTotal();
    	
    	return view('hr.alert.list',compact('totalCnicExpire','appointmentExpiryTotal','drivingLicenceExpiryTotal','pecCardExpiryTotal'));
	}

	public function cnicExpiryDetail(){
		$nextTenDays = \Carbon\Carbon::now()->addDays(10)->format('Y-m-d');
		$employees = HrEmployee::where('hr_status_id',1)->where('cnic_expiry','<',$nextTenDays)->with('employeeProject','employeeOffice')->get();

      	foreach ($employees as $employee){
    		$data [] = array(
				"employee_name" => employeeFullName($employee->id),
                "employee_project"=>$employee->employeeProject->last()->name??'',
                "employee_office"=>$employee->employeeOffice->last()->name??'',
				"cnic_expiry_date" =>\Carbon\Carbon::parse( $employee->cnic_expiry)->format('M d, Y'),
                "mobile"=>$employee->hrContactMobile->mobile??'',
			);  			
    	}

    	usort($data, function($a, $b) {
    		return strtotime($a['cnic_expiry_date']) - strtotime($b['cnic_expiry_date']);
		});

    	return response()->json(['status'=> 'Ok', 'full_name'=>'CNIC Expiry Detail', 'cnicExpiry'=>$data]);
	}

	public function appointmentExpiry(){
		$nextTenDays = \Carbon\Carbon::now()->addDays(10)->format('Y-m-d');
		$employees = HrEmployee::where('hr_status_id',1)->with('employeeAppointment','employeeOffice','employeeProject')->get();

		foreach ($employees as $key => $employee) {                   
            if($employee->employeeAppointment->expiry_date??''!=''){
                if($employee->employeeAppointment->expiry_date<$nextTenDays){
            		$data [] = array(
					"employee_name" => employeeFullName($employee->id),
                    "employee_project"=>$employee->employeeProject->last()->name??'',
                    "employee_office"=>$employee->employeeOffice->last()->name??'',
					"appointment_expiry_date" =>  \Carbon\Carbon::parse( $employee->employeeAppointment->expiry_date)->format('M d, Y'),
                    "mobile"=>$employee->hrContactMobile->mobile??'',
					);  	
                    
                }
            }
            
        }

    	usort($data, function($a, $b) {
    		return strtotime($a['appointment_expiry_date']) - strtotime($b['appointment_expiry_date']);
		});

    	return response()->json(['status'=> 'Ok', 'full_name'=>'Appointment Expiry Detail', 'appointmentExpiry'=>$data]);
	}

    public function drivingLicenceExpiry(){
        $nextTenDays = \Carbon\Carbon::now()->addDays(10)->format('Y-m-d');
        $employees = HrEmployee::where('hr_status_id',1)->with('employeeDesignation','employeeProject','employeeOffice','hrDriving')->get();

        foreach ($employees as $key => $employee) {                   
            if($employee->employeeDesignation->last()->name??''=='Driver'){
                if($employee->hrDriving->licence_expiry??''!=''){
                    if($employee->hrDriving->licence_expiry<$nextTenDays){
                        $data [] = array(
                        "employee_name" => employeeFullName($employee->id),
                        "employee_project"=>$employee->employeeProject->last()->name??'',
                        "employee_office"=>$employee->employeeOffice->last()->name??'',
                        "licence_expiry_date" => \Carbon\Carbon::parse($employee->hrDriving->licence_expiry)->format('M d, Y'),
                        "mobile"=>$employee->hrContactMobile->mobile??'',
                        );      
                        
                    }
                }
            }   
        }

        usort($data, function($a, $b) {
            return strtotime($a['licence_expiry_date']) - strtotime($b['licence_expiry_date']);
        });

        return response()->json(['status'=> 'Ok', 'full_name'=>'Driver Licence Expiry Detail', 'drivingLicenceExpiryTotal'=>$data]);
    }

    public function pecCardExpiry(){
        $nextTenDays = \Carbon\Carbon::now()->addDays(10)->format('Y-m-d');
        $employees = HrEmployee::where('hr_status_id',1)->with('hrMembership','employeeOffice','employeeProject')->get();

        foreach ($employees as $key => $employee) {                   
            
            if($employee->hrMembership->expiry??''!=''){
                if($employee->hrMembership->expiry<$nextTenDays){
                    $data [] = array(
                    "employee_name" => employeeFullName($employee->id),
                    "employee_project"=>$employee->employeeProject->last()->name??'',
                    "employee_office"=>$employee->employeeOffice->last()->name??'',
                    "pec_expiry_date" => \Carbon\Carbon::parse($employee->hrMembership->expiry)->format('M d, Y'),
                    "mobile"=>$employee->hrContactMobile->mobile??'',
                    );      
                    
                }
            }
             
        }

        usort($data, function($a, $b) {
            return strtotime($a['pec_expiry_date']) - strtotime($b['pec_expiry_date']);
        });

        return response()->json(['status'=> 'Ok', 'full_name'=>'PEC Card Expiry Detail', 'pecCardExpiry'=>$data]);
    }


}
