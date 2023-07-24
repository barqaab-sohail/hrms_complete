<?php

use App\Models\Hr\HrEmployee;
use App\Models\Leave\Leave;
use App\Models\Leave\LeAccumulative;
use App\User;


function casualLeave($id)
{
    $employee = HrEmployee::with('employeeAppointment')->find($id);
    $joiningDate = $employee->employeeAppointment->joining_date ?? '';
    $startDate = date("Y") . '-01-01';
    $endDate = date("Y") . '-12-31';

    //check total casual leave balance
    $totalCasualLeave = 0;
    if ($joiningDate < $startDate) {
        $totalCasualLeave = 12;
    } else {
        $startTimeStamp = strtotime($joiningDate);
        $endTimeStamp = strtotime($endDate);

        $timeDiff = abs($endTimeStamp - $startTimeStamp);

        $numberDays = $timeDiff / 86400;  // 86400 seconds in one day

        // and you might want to convert to integer
        $numberDays = round($numberDays);

        $totalCasualLeave = round(12 *  $numberDays / 365);
    }

    if ($employee) {

        // Only approved leave
        $leaveAvailed = Leave::join('le_sanctioneds', 'le_sanctioneds.leave_id', '=', 'leaves.id')->select('leaves.*', 'le_sanctioneds.le_status_type_id')->where('hr_employee_id', $employee->id)->where('le_type_id', 1)->whereDate('from', ">=", $startDate)->whereDate('to', "<=", $endDate)->where('le_status_type_id', 1)->sum('days');


        $remainingLeaves = $totalCasualLeave - $leaveAvailed;

        return $remainingLeaves;
    }
}

function leaveStatusType($id)
{

    if ($id == 1) {
        return 'Approved';
    } else if ($id == 2) {
        return 'Rejected';
    } else {
        return 'Pending';
    }
}

function annualTotalLeaveBalance($employeeId)
{

    $employee = HrEmployee::with('employeeCategory')->find($employeeId);
    $category = $employee->employeeCurrentCategory->name ?? '';
    if ($category == 'A') {
        $startFrom = '2022-01-01';
        $currentYear = date("Y");
        // . '-12-31';
        $accommulateLeaves = 0;
        for ($i = 2022; $i <= $currentYear; $i++) {
            $from = $i . '-01-01';
            $to = $i . '-12-31';
            $totalLeave = Leave::where(['hr_employee_id' => $employeeId, 'le_type_id' => 2])->whereDate('from', ">=", $from)->whereDate('to', "<=", $to)->sum('days');
            $totalLeave = 18 - $totalLeave;
            if ($totalLeave > 0) {
                $accommulateLeaves += $totalLeave;
            }
        }

        //accomulative leave balance upto 31-12-2021;
        $previousLeaves = LeAccumulative::where('hr_employee_id', $employeeId)->first();
        // if ($previousLeaves) {
        //     $perviousTotal = $previousLeaves->accumulative_total;
        // } else {
        //     $perviousTotal = 0;
        // }
        //this line support by php version 8 null check 
        $total =  $accommulateLeaves + $previousLeaves?->accumulative_total ?? 0;

        //Maximum accomulate 60 + current year annual leaves = 78
        if ($total > 78) {
            $total = 78;
        }

        return $total;
    }
    return 0;
}

function annualLeave($employeeId)
{

    $employee = HrEmployee::with('employeeCategory')->find($employeeId);
    $category = $employee->employeeCurrentCategory->name ?? '';

    if ($category == 'A') {
        $categoryADate = $employee->employeeCatA->effective_date; //'2022-05-01';
        $startDate = date("Y") . '-01-01';
        $endDate = date("Y") . '-12-31';
        $currentYearAnnualLeave = 0;
        if ($categoryADate < $startDate) {
            $currentYearAnnualLeave = 18;
        } else {
            $startTimeStamp = strtotime($categoryADate);
            $endTimeStamp = strtotime($endDate);

            $timeDiff = abs($endTimeStamp - $startTimeStamp);

            $numberDays = $timeDiff / 86400;  // 86400 seconds in one day

            // and you might want to convert to integer
            $numberDays = round($numberDays);

            $currentYearAnnualLeave = round(18 *  $numberDays / 365);
        }

        if ($employee) {

            $leaveAvailed = Leave::join('le_sanctioneds', 'le_sanctioneds.leave_id', '=', 'leaves.id')->select('leaves.*', 'le_sanctioneds.le_status_type_id')->where('hr_employee_id', $employee->id)->where('le_type_id', 2)->whereDate('from', ">=", $startDate)->whereDate('to', "<=", $endDate)->where('le_status_type_id', 1)->sum('days');

            $remainingLeaves = $currentYearAnnualLeave - $leaveAvailed;
            return $remainingLeaves;
        }
    } else {

        return 'N/A';
    }
}

function leaveEmployees()
{

    return $leaveEmployees = [1000001, 1000002, 1000004, 1000005, 1000006, 1000007, 1000009, 1000010, 1000040, 1000110, 1000124, 1000126, 1000127, 1000130, 1000131, 1000133, 1000136, 1000137, 1000138, 1000139, 1000141, 1000144, 1000145, 1000146, 1000147, 1000148, 1000149, 1000151, 1000152, 1000153, 1000154, 1000155, 1000156, 1000157, 1000158, 1000159, 1000160, 1000161, 1000167, 1000169, 1000178, 1000274, 1000405, 1000440, 1000008, 1000011, 1000012, 1000013, 1000171, 1000172, 1000173, 1000174, 1000177, 1000181, 1000182, 1000412, 1000183, 1000383, 100164, 1000322];
}
