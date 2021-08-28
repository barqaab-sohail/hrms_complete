<?php
use App\Models\Cv\CvSpecialization;
use App\Models\CV\CvDetail;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\EmployeeManager;
use App\Models\Hr\EmployeeDesignation;
use App\Models\Hr\HrDesignation;
use App\Models\Office\Office;

function employeeFullName($id){
	$hremployee = HrEmployee::find($id);
		if($hremployee){
			
			$fullName = $hremployee->first_name. ' '.$hremployee->last_name;

			$designation = isset($hremployee->employeeDesignation->last()->name)?$hremployee->employeeDesignation->last()->name:'';
			
			return $fullName.' - '.$designation;
		}
}

function generateEmployeeId(){
	$employeeId = 1000750;
	
	while(HrEmployee::where('employee_no',$employeeId)->count()>0){ 
            $employeeId++;  
    }

    return $employeeId;
}


function checkHod($id){

	//$hod = EmployeeManager::where('hr_manager_id',$id)->get();

	$result = collect(HrEmployee::join('employee_managers','employee_managers.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','employee_managers.hr_manager_id','employee_managers.effective_date')->where('hr_status_id',1)->orderBy('effective_date','desc')->get());
	$resultUnique = ($result->unique('id'));
	$resultUnique->values()->all();
	$result = $resultUnique->where('hr_manager_id',$id);

	return $result;


}

function appointmentExpiryTotal(){

		$nextTenDays = \Carbon\Carbon::now()->addDays(10)->format('Y-m-d');
		$employees = HrEmployee::where('hr_status_id',1)->with('employeeAppointment')->get();
        $total = 0;
        $today = \Carbon\Carbon::now();
        foreach ($employees as $key => $employee) {                   
            if($employee->employeeAppointment->expiry_date??''!=''){
                if($employee->employeeAppointment->expiry_date<$nextTenDays){
            		$total++;    
                }
            }
            
        }
        
	return $total;
}

function cnicExpiryTotal(){
	$nextTenDays = \Carbon\Carbon::now()->addDays(10)->format('Y-m-d');
		$employees = HrEmployee::where('hr_status_id',1)->get();
        $total = 0;
        $today = \Carbon\Carbon::now();
        foreach ($employees as $key => $employee) {                   
            if($employee->cnic_expiry??''!=''){
                if($employee->cnic_expiry<$nextTenDays){
            		$total++;    
                }
            }
            
        }
        
	return $total;
}

function drivingLicenceExpiryTotal(){
	$nextTenDays = \Carbon\Carbon::now()->addDays(10)->format('Y-m-d');
		$employees =  HrEmployee::where('hr_status_id',1)->with('employeeDesignation','hrDriving')->get();
        $total = 0;
        $today = \Carbon\Carbon::now();
        foreach ($employees as $key => $employee) {                   
            if($employee->employeeDesignation->last()->name??''=='Driver'){
            	if($employee->hrDriving->licence_expiry??''!=''){
		            if($employee->hrDriving->licence_expiry<$nextTenDays){
		        		$total++;    
		            }
		        }
            }  
        }   
	return $total;
}

function pecCardExpiryTotal(){
	$nextTenDays = \Carbon\Carbon::now()->addDays(10)->format('Y-m-d');
		$employees =  HrEmployee::where('hr_status_id',1)->with('hrMembership')->get();
        $total = 0;
        $today = \Carbon\Carbon::now();
        foreach ($employees as $key => $employee) {                   
            
        	if($employee->hrMembership->expiry??''!=''){
	            if($employee->hrMembership->expiry<$nextTenDays){
	        		$total++;    
	            }
	        }
             
        }   
	return $total;
}





function officeName($id){
	$office = Office::find($id);
	return $office->name;
}



function cvSpecilizationName($id){

    $cvSpecialization = CvSpecialization::find($id);

    return $cvSpecialization->name;
    
   
}

function fullName($id){

	$cvDetail = CvDetail::find($id);

	return $cvDetail->full_name;
}


function age($dob) {
    //return $dob->diffInYears(\Carbon::now());
	$years = \Carbon\Carbon::parse($dob)->age;
    return $years;
}

function getDivision($id){

	if($id==11){

		return "Water";
	}else if ($id==12){
			return "Power";
	}else if ($id==13){
			return "Head Office";
	}else if ($id==14){
			return "Finance";
	}else{
		return "No Code";
	}

}



