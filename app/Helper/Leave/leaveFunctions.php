<?php
use App\Models\Hr\HrEmployee;
use App\Models\Leave\Leave;
use App\User;


function casualLeave($id){
	$employee = HrEmployee::find($id);
    $joiningDate = $employee->employeeAppointment->joining_date;
    $startDate = date("Y").'-01-01';
    $endDate = date("Y").'-12-31';

     //check total casual leave balance
        $totalCasualLeave=0;
        if($joiningDate<$startDate){
            $totalCasualLeave =12;
        }else{
            $startTimeStamp = strtotime($joiningDate);
            $endTimeStamp = strtotime($endDate);

            $timeDiff = abs($endTimeStamp - $startTimeStamp);

            $numberDays = $timeDiff/86400;  // 86400 seconds in one day

            // and you might want to convert to integer
            $numberDays = intval($numberDays);

            $totalCasualLeave = intval(12 *  $numberDays / 365);
        }

	if($employee){		
		$remainingLeaves = $totalCasualLeave - Leave::where('hr_employee_id',$employee->id)->where('le_type_id',1)->whereDate('from', ">=", $startDate)->whereDate('to', "<=",$endDate)->sum('days');
		return $remainingLeaves;
	}
}

function leaveStatusType($id){

	if($id == 1){
		return 'Approved';
	}else if ($id ==2){
		return 'Rejected';
	}
}

function annualLeave($employeeId){

	$employee = HrEmployee::find($employeeId);

	$category = $employee->employeeCategory->last()->name??'';
	

    if($category == 'A'){
	    $categoryADate = '2022-01-01';
	    $startDate = date("Y").'-01-01';
	    $endDate = date("Y").'-12-31';
	    $currentYearAnnualLeave=0;
	    if($categoryADate<$startDate){
            $currentYearAnnualLeave =18;
        }else{
            $startTimeStamp = strtotime($categoryADate);
            $endTimeStamp = strtotime($endDate);

            $timeDiff = abs($endTimeStamp - $startTimeStamp);

            $numberDays = $timeDiff/86400;  // 86400 seconds in one day

            // and you might want to convert to integer
            $numberDays = round($numberDays);

            $currentYearAnnualLeave = round(18 *  $numberDays / 365);
        }

        if($employee){		
		$remainingLeaves = $currentYearAnnualLeave - Leave::where('hr_employee_id',$employee->id)->where('le_type_id',2)->whereDate('from', ">=", $startDate)->whereDate('to', "<=",$endDate)->sum('days');
		return $remainingLeaves;
	}

	}else{

		return 0;
	}


}

