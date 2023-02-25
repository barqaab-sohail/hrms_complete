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
        // $designations = employeeDesignationArray();
        // $data = $data->sort(function ($a, $b) use ($designations) {
        //     $pos_a = array_search($a->designation ?? '', $designations);
        //     $pos_b = array_search($b->designation ?? '', $designations);
        //     return  $pos_a !== false ? $pos_a - $pos_b : 999999;
        // });

        $first = array('1000124', '1000274', '1000110', '1000001', '1000151', '1000182', '1000155', '1000160', '1000139', '1000145', '1000147', '1000173', '1000174', '1000181', '1000171', '1000040');
        $second = range(1000001, 1099999);
        $employeeNos = array_merge($first,  $second);

        $data =  $data->sortBy(function ($model) use ($employeeNos) {
            return array_search($model->employee_no, $employeeNos);
        });

        //   // second sort with respect to Hr Status
        $hrStatuses = array('On Board', 'Resigned', 'Terminated', 'Retired', 'Long Leave', 'ManMonth Ended', 'Death');

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
                "id" => $employee->id ?? '',
                "employee_no" => $employee->employee_no ?? '',
                "full_name" => $employee->full_name ?? '',
                "date_of_birth" => \Carbon\Carbon::parse($employee->date_of_birth)->format('M d, Y') ?? '',
                "date_of_joining" => \Carbon\Carbon::parse($employee->employeeAppointment->joining_date ?? '')->format('M d, Y') ?? '',
                "cnic" => $employee->cnic ?? '',
                "designation" => $employee->designation ?? '',
                "picture" => $picture,
                "mobile" => $employee->hrContactMobile->mobile ?? '',
                "status" => $employee->hr_status_id ?? ''
            );
        }

        return response()->json($employees);
    }
}
