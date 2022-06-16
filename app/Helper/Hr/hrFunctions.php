<?php
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrPromotion;
use App\Models\Hr\HrPosting;
use App\User;


function employeeFullName($id){
	$hremployee = HrEmployee::find($id);
	if($hremployee){	
		$fullName = $hremployee->first_name. ' '.$hremployee->last_name;
		$designation = isset($hremployee->employeeDesignation->last()->name)?$hremployee->employeeDesignation->last()->name:'';
		return $fullName.' - '.$designation;
	}
}

function userFullName($id){
	$user = User::find($id);
	if($user){	
		$fullName = $user->hrEmployee->first_name. ' '.$user->hrEmployee->last_name;
		return $fullName;
	}
}

function promotionDocument($id){
	$hrDocument=HrPromotion::where('hr_documentation_id',$id)->first();

	if($hrDocument){
		return false;
	}else{
		return true;
	}
}

function postingDocument($id){
	$hrDocument=HrPosting::where('hr_documentation_id',$id)->first();

	if($hrDocument){
		return false;
	}else{
		return true;
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

function employeeDesignationArray(){

	$designationArray = array('testing','Chief Executive Officer', 'General Manager (W&C)', 'General Manager (Power)', 'Manager Finance', 'Deputy Manager HR&A', 'Chief Project Manager', 'Project Manager', 'Chief Engineer', 'Chief Contract Engineer', 'Chief Engineer Design', 'Resident Project Manager', 'Chief Resident Engineer', 'Financial Expert', 'Planning Procurement Expert', 'Principal Engineer Telecom  Microwave Expert', 'Geo Technical Expert', 'Testing And Inspection Expert', 'Scada Design Engineering  Integration', 'Microwave Expert', 'Principal Engineer', 'Principal Engineer (Electrical)', 'Principal Engineer (Civil)', 'Principal Reports and Documents', 'Principal Engineer for Flood & Emergent', 'Principal Mechanical Engineer', 'Project Monitoring and Evaluation Advisor', 'Community Development Specialist', 'Irrigation Expert', 'Financial Management Advisor', 'Public Relations Advisor', 'Environmental Management Advisor', 'Economic Advisor', 'Social & resettlement Advisor', 'Construction Supervision Telecom', 'Construction Manager', 'Mis Expert','Quality Control Specialist','Resident Engineer', 'Deputy Resident Project Manager','Assistant Resident Engineer', 'Deputy Manager Finance', 'Deputy Project Manager', 'Legal Consultant', 'IT Coordinator','Senior Engineer', 'Senior Engineer (Electrical)', 'Resettelment Specialsit', 'Senior Engineer (Civil)', 'Senior Structural Engineer', 'Senior Geo-Technical Engineer', 'Senior Geologist', 'Senior Construction Engineer Mechanical','Senior Office Engineer', 'Senior Statistical Analyst', 'Procurement and Contract Engineer', 'Material Engineer', 'Senior Environmentalist', 'Senior Area Manager', 'Support System Study Engineer', 'Construction Supervision Engineer', 'Junior Engineer', 'Junior Engineer (Electrical)', 'Junior Engineer (Civil)', 'Senior Accounts Officer', 'Accounts Officer', 'Senior Supervision Engineer', 'Site Inspector','Secretary', 'Senior Accountant', 'Audit Officer',  'Computer Processing Officer','Site Engineer','Overseer Civil', 'Office Supervisor', 'Patwari', 'Lineman', 'Office Manager', 'Junior Financial Analyst ', 'Sub Engineer Electrical', 'Sub Engineer Civil', 'Area Inspector', 'Area Manager', 'Cluster Manager', 'Assistant Engineer Civil', 'Assistant Accounts Officer', 'PS to Chief Executive','Accountant Cum Offiec Manager', 'Computer Operator', 'AutoCAD Operator', 'Field Inspector', 'Inspector Civil', 'Inspector Electrical', 'Line Foreman', 'Line Inspector', 'Office Account Assistant', 'Quality Control Inspector', 'Surveyor', 'Accountant', 'Accounts Assistant', 'Accounts Clerk', 'Junior Clerk', 'Draftsman','Office Assistant Record Keeper', 'Technical Assistant', 'Record Keeper', 'Supervisor Electrical', 'Caretaker', 'Driver', 'Utility Person', 'Security Guard', 'Khalasi', 'Office Assistant', 'Field Helper', 'Utility PersonPeon','Cook', 'Sanitary Worker', 'Electrician', 'Office Helper', 'Messenger', 'Kitchen Helper', 'Sweeper ', 'Janitor', 'Mali', 'Office Boy', 'Naib Qasid', 'Sweeper (Part Time)','ChowkidarWatchman','WatchmanCleaner','Part Time Gardner','');

	return $designationArray;
}


