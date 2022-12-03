<?php

namespace App\Http\Controllers\MIS\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDocumentation;

class EmployeeController extends Controller
{
    public function index()
    {

        $data = HrEmployee::with('employeeDesignation', 'picture', 'employeeProject', 'employeeOffice', 'employeeAppointment', 'hrContactMobile')->get();

        //first sort with respect to Designation
        $designations = employeeDesignationArray();
        $data = $data->sort(function ($a, $b) use ($designations) {
            $pos_a = array_search($a->designation ?? '', $designations);
            $pos_b = array_search($b->designation ?? '', $designations);
            return  $pos_a !== false ? $pos_a - $pos_b : 999999;
        });

        //   // second sort with respect to Hr Status
        $hrStatuses = array('On Board', 'Resigned', 'Terminated', 'Retired', 'Long Leave', 'Manmonth Ended', 'Death');

        $data = $data->sort(function ($a, $b) use ($hrStatuses) {
            $pos_a = array_search($a->hr_status_id ?? '', $hrStatuses);
            $pos_b = array_search($b->hr_status_id ?? '', $hrStatuses);
            return $pos_a - $pos_b;
        });

        $defaultPicture = asset('Massets/images/default.png');
        foreach ($data as $employee) {
            // $picture = HrDocumentation::where('hr_employee_id', $employee->id)->where('description', 'Picture')->first();
            if ($employee->picture) {
                $picture = asset('storage/' . $employee->picture->path . $employee->picture->file_name);
            } else {
                $picture = $defaultPicture;
            }

            $employees[] =  array(
                "id" => $employee->id,
                "employee_no" => $employee->employee_no,
                "full_name" => $employee->full_name,
                "date_of_birth" => \Carbon\Carbon::parse($employee->date_of_birth)->format('M d, Y'),
                "date_of_joining" => \Carbon\Carbon::parse($employee->employeeAppointment->joining_date ?? '')->format('M d, Y'),
                "cnic" => $employee->cnic,
                "designation" => $employee->designation,
                "picture" => $picture,
                "mobile" => $employee->hrContactMobile->mobile ?? '',
                "status" => $employee->hr_status_id ?? ''
            );
        }

        return response()->json($employees);
    }
}
