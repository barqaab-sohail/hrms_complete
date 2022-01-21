<?php
use App\Models\Hr\HrEmployee;
use App\Models\Leave\Leave;
use App\User;


function casualLeave($id){
	$hremployee = HrEmployee::find($id);

	if($hremployee){		
		$leaveTaken = 12 - Leave::where('hr_employee_id',$hremployee->id)->where('le_type_id',1)->whereDate('from', ">=", '2022-01-01')->whereDate('to', "<=",'2022-12-31')->sum('days');
		return $leaveTaken;
	}
}

function checkLeave($employeeId, $from, $to, $leaveType){




}

