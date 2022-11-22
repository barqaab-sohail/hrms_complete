<?php

use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrPromotion;
use App\Models\Hr\HrPosting;
use App\User;
use App\Models\MisUser;

function isAllowMis($userId)
{
	$misUser = MisUser::where('user_id', $userId)->first();
	if ($misUser) {
		return $misUser->is_allow_mis;
	} else {
		return false;
	}
}


function employeeFullName($id)
{
	$hremployee = HrEmployee::find($id);
	if ($hremployee) {
		$fullName = $hremployee->first_name . ' ' . $hremployee->last_name;
		$designation = isset($hremployee->employeeDesignation->last()->name) ? $hremployee->employeeDesignation->last()->name : '';
		return $fullName . ' - ' . $designation;
	}
}

function userFullName($id)
{
	$user = User::find($id);
	if ($user) {
		$fullName = $user->hrEmployee->first_name . ' ' . $user->hrEmployee->last_name;
		return $fullName;
	}
}

function promotionDocument($id)
{
	$hrDocument = HrPromotion::where('hr_documentation_id', $id)->first();

	if ($hrDocument) {
		return false;
	} else {
		return true;
	}
}

function postingDocument($id)
{
	$hrDocument = HrPosting::where('hr_documentation_id', $id)->first();

	if ($hrDocument) {
		return false;
	} else {
		return true;
	}
}

function generateEmployeeId()
{
	$employeeId = 1000750;

	while (HrEmployee::where('employee_no', $employeeId)->count() > 0) {
		$employeeId++;
	}

	return $employeeId;
}

function checkHod($id)
{

	$result = collect(HrEmployee::join('employee_managers', 'employee_managers.hr_employee_id', '=', 'hr_employees.id')->select('hr_employees.*', 'employee_managers.hr_manager_id', 'employee_managers.effective_date')->where('hr_status_id', 1)->orderBy('effective_date', 'desc')->get());
	$resultUnique = ($result->unique('id'));
	$resultUnique->values()->all();
	$result = $resultUnique->where('hr_manager_id', $id);

	return $result;
}

function appointmentExpiryTotal()
{

	$nextTenDays = \Carbon\Carbon::now()->addDays(10)->format('Y-m-d');
	$employees = HrEmployee::where('hr_status_id', 1)->with('employeeAppointment')->get();
	$total = 0;
	$today = \Carbon\Carbon::now();
	foreach ($employees as $key => $employee) {
		if ($employee->employeeAppointment->expiry_date ?? '' != '') {
			if ($employee->employeeAppointment->expiry_date < $nextTenDays) {
				$total++;
			}
		}
	}
	return $total;
}

function cnicExpiryTotal()
{
	$nextTenDays = \Carbon\Carbon::now()->addDays(10)->format('Y-m-d');
	$employees = HrEmployee::where('hr_status_id', 1)->get();
	$total = 0;
	$today = \Carbon\Carbon::now();
	foreach ($employees as $key => $employee) {
		if ($employee->cnic_expiry ?? '' != '') {
			if ($employee->cnic_expiry < $nextTenDays) {
				$total++;
			}
		}
	}
	return $total;
}

function drivingLicenceExpiryTotal()
{
	$nextTenDays = \Carbon\Carbon::now()->addDays(10)->format('Y-m-d');
	$employees =  HrEmployee::where('hr_status_id', 1)->with('employeeDesignation', 'hrDriving')->get();
	$total = 0;
	$today = \Carbon\Carbon::now();
	foreach ($employees as $key => $employee) {
		if ($employee->employeeDesignation->last()->name ?? '' == 'Driver') {
			if ($employee->hrDriving->licence_expiry ?? '' != '') {
				if ($employee->hrDriving->licence_expiry < $nextTenDays) {
					$total++;
				}
			}
		}
	}
	return $total;
}

function pecCardExpiryTotal()
{
	$nextTenDays = \Carbon\Carbon::now()->addDays(10)->format('Y-m-d');
	$employees =  HrEmployee::where('hr_status_id', 1)->with('hrMembership')->get();
	$total = 0;
	$today = \Carbon\Carbon::now();
	foreach ($employees as $key => $employee) {

		if ($employee->hrMembership->expiry ?? '' != '') {
			if ($employee->hrMembership->expiry < $nextTenDays) {
				$total++;
			}
		}
	}
	return $total;
}

function editNotification($oldObject)
{

	foreach ($oldObject as $key => $value) {
		echo "$key => $value\n";
	}
}


function employeeDesignationArray()
{
	$managements = array('testing', 'Chief Executive Officer', 'General Manager (W&C)', 'General Manager (Power)', 'Manager Finance', 'Deputy Manager HR&A');
	$projectManagers =  array('Regional Manager South', 'Chief Project Manager', 'Project Manager', 'Chief Engineer', 'Chief Contract Engineer', 'Chief Engineer STG', 'Chief Engineer Design', 'Resident Project Manager', 'Chief Resident Engineer');
	$seniorEngineers = array('Porject Coordinator', 'GIS Exprt', 'Telecom Expert', 'Expert Environment', 'Structural Design Engineer', 'Expert Load Flow Studies', 'WPO Expert Documentation Office', 'Seismic Expert', 'Regional Project Manager', 'Financial Expert', 'Expert Transmission Line', 'Resettlement Specialist', 'Gender Expert', 'Planning Procurement Expert', 'Expert Structural Design', 'Construction Supervision Engineer Civil', 'Expert Enviroonmental Health And Safety expert', 'Principal Engineer Telecom  Microwave Expert', 'Geo Technical Expert', 'Testing And Inspection Expert', 'Economist', 'Sociological Expert', 'Scada Design Engineering  Integration', 'Microwave Expert', 'Principal Engineer', 'Principal Engineer (Electrical)', 'Expert Ground Water', 'Expert Agronomist', 'Survey Expert', 'Agronomist', 'Principal Engineer (Civil)', 'Principal Environmental Engineer', 'Principal Reports and Documents', 'Principal Engineer for Flood & Emergent', 'Principal Soil Specialist', 'Principal Mechanical Engineer', 'Principal Engineer Design', 'Project Monitoring and Evaluation Advisor', 'Hydrology Modeling Expert', 'Hydraulic Design Expert', 'Community Development Specialist', 'Environmental And Social Safeguards Expert', 'Irrigation Expert', 'Financial Management Advisor', 'Public Relations Advisor', 'Contract Expert', 'Environmental Management Advisor', 'Economic Advisor', 'Monitoring  Evaluation Engineer', 'Social & resettlement Advisor', 'Construction Supervision Telecom', 'Construction Manager', 'Mis Expert', 'Quality Control Specialist', 'Resident Engineer', 'Principal Economist', 'Resident Engineer Mechanical', 'Deputy Resident Project Manager', 'Assistant Resident Engineer', 'Construction Engineer', 'Deputy Manager Finance', 'Deputy Project Manager', 'Legal Consultant', 'IT Coordinator', 'Senior Engineer', 'Senior Engineer (Electrical)', 'Resettlement Specialist', 'Senior Engineer (Civil)', 'Senior Structural Engineer', 'Senior Geo-Technical Engineer', 'Senior Hydraulic Engineer', 'Senior Geologist', 'Senior Construction Engineer Mechanical', 'Senior Office Engineer', 'Senior Statistical Analyst', 'Procurement and Contract Engineer', 'Material Engineer', 'Senior Environment', 'Senior Area Manager', 'Senior Environment Engineer', 'Supervision Expert Electrical', 'Support System Study Engineer', 'Construction Supervision Engineer');
	$juniors = array('Senior Mechanical Electrical Engineer', 'Planning Scheduling Engineer', 'Electrical Engineer', 'Contract Engineer', 'Junior Engineer Drainage', 'Junior Engineer', 'Junior Engineer (Electrical)', 'Junior Engineer (Civil)', 'Junior Mechanical Electrical Engineer', 'Civil Engineer', 'Junior Structure Engineer', 'Junior Sociologist', 'Business Officer', 'Junior Geologist', 'Senior Accounts Officer', 'Accounts Officer', 'Senior Supervision Engineer', 'Civl Engineer', 'Site Inspector', 'Secretary', 'Senior Accountant', 'Senior Construction Engineer Civil', 'Junior GIS Analyst', 'Audit Officer', 'Business Development Assistant', 'Human Resources Assistant', 'BD / IT Coordinator', 'IT Coordinator',  'Computer Processing Officer', 'Site Engineer');
	$support = array('Computer Operator Cum Office Manager', 'Auto CAD Draftsman', 'Overseer Civil', 'Inspector', 'Laboratory Technician', 'Geotechnical Engineer', 'Office Supervisor', 'Supervisor Electrical', 'Patwari', 'Lineman', 'Office Manager', 'Junior Hydraulic Engineer', 'Junior Financial Analyst ', 'Sub Engineer Electrical', 'Sub Engineer Civil', 'Area Inspector', 'Area Manager', 'Cluster Manager', 'Area Manager Part Time', 'Assistant Manager', 'Assistant Engineer Civil', 'Assistant Accounts Officer', 'PS to Chief Executive', 'Accountant Cum Offiec Manager', 'Computer Operator', 'AutoCAD Operator', 'Field Inspector', 'Inspector Civil', 'Inspector Electrical', 'Line Foreman', 'Line Inspector', 'Office Account Assistant', 'Quality Control Inspector', 'Surveyor', 'Accountant', 'Accounts Assistant', 'Accounts Clerk', 'Junior Clerk', 'Draftsman', 'Office Assistant Record Keeper', 'Technical Assistant');
	$utilityPerson = array('Record Keeper', 'Driver Cum Utility Person Part Time', 'Field Coordinator', 'Sanitary Worker Part Time', 'Security Guard Part Time', 'Utility Person Part Time', 'Work Supervisor', 'Caretaker', 'Driver', 'Utility Person', 'Security Guard', 'Khalasi', 'Office Assistant', 'Field Helper', 'Utility PersonPeon', 'Cook', 'Sanitary Worker', 'Electrician', 'Office Helper', 'Messenger', 'Kitchen Helper', 'Utility Person Cook', 'Office Attendant', 'Sweeper ', 'Janitor', 'Mali', 'Office Boy', 'Naib Qasid', 'Sweeper (Part Time)', 'Office Boy Cum Mali', 'ChowkidarWatchman', 'WatchmanCleaner', 'Part Time Gardner', 'Sweeper Sanitary Worker', '');
	$others = array('Sociological Ecologist');
	$designationArray = array_merge($managements, $projectManagers, $seniorEngineers, $juniors, $support, $utilityPerson, $others);
	return $designationArray;
}
