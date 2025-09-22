<?php

namespace App\Http\Controllers\MIS\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDocumentation;
use Illuminate\Support\Facades\Cache;

class EmployeeController extends Controller
{
    public function employee($id)
    {
        // Find the employee with all relationships
        $data = HrEmployee::with(
            'hrContactMobile',
            'employeeCurrentProject',
            'employeeAppointment',
            'hrBloodGroup',
            'hrDocumentations',
            'hrEducation',
            'hrEducation.education', // Eager load the education relationship
            'hrEmergency',
            'hrExperiences',
            'hrContactEmail',
            'hrContactPermanent'
        )->find($id);

        // Check if employee exists
        if (!$data) {
            return response()->json([
                'error' => 'Employee not found'
            ], 404);
        }

        // Initialize educations array
        $educations = [];

        // Process education data only if it exists
        if ($data->hrEducation && $data->hrEducation->isNotEmpty()) {
            foreach ($data->hrEducation as $education) {
                $educations[] = [
                    'institute' => $education->institute ?? '',
                    'degree' => $education->education->degree_name ?? '',
                    'from' => $education->from ?? '',
                    'to' => $education->to ?? ''
                ];
            }
        }

        // Safely access current_salary (assuming it might be JSON/array)
        $currentSalary = is_array($data->current_salary) ? $data->current_salary : (array)$data->current_salary;

        // Build employee data with null-safe operators
        $employee = [
            'full_name' => ($data->first_name ?? '') . ' ' . ($data->last_name ?? ''),
            'father_name' => $data->father_name ?? '',
            'designation' => $data->employeeCurrentDesignation?->name ?? '',
            'picture' => $data->picture ?? '',
            'cnic' => $data->cnic ?? '',
            'joining_date' => $data->employeeAppointment?->joining_date ? \Carbon\Carbon::parse($data->employeeAppointment->joining_date)->format('M d, Y') : '',
            'date_of_birth' => $data->date_of_birth ? \Carbon\Carbon::parse($data->date_of_birth)->format('M d, Y') : '',
            'project' => $data->employeeCurrentProject?->name ?? '',
            'hr_status_id' => $data->hr_status_id ?? '',
            'current_salary' => $currentSalary['salary'] ?? '',
            'salary_effective_date' => $currentSalary['effective_date'] ?? '',
            'hr_blood_group' => $data->hrBloodGroup?->name ?? '',
            'mobile' => $data->hrContactMobile?->mobile ?? '',
            'emgergencyContactName' => $data->hrEmergency?->name ?? '',
            'emgergencyContactRelaction' => $data->hrEmergency?->relation ?? '',
            'emgergencyContact' => $data->hrEmergency?->mobile ?? '',
            'email' => $data->hrContactEmail?->email ?? '',
            'address' => $data->hrContactPermanent?->complete_address ?? '',
            'experiences' => $data->hrExperiences ?? [],
            'educations' => $educations,
            'documents' => $data->hrDocumentations ?? [],
        ];

        return response()->json($employee);
    }
    public function index(bool $cacheStatus = true)
    {

        if (Cache::has('employeeList') && $cacheStatus) {
            $data = Cache::get('employeeList');
            return response()->json($data);
        }


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

        //$defaultPicture = asset('Massets/images/default.png');
        foreach ($data as $employee) {
            // $picture = HrDocumentation::where('hr_employee_id', $employee->id)->where('description', 'Picture')->first();
            // if ($employee->picture) {
            //     $picture = asset('storage/' . $employee->picture->path . $employee->picture->file_name);
            // } else {
            //     $picture = $defaultPicture;
            // }

            $employees[] =  array(
                "id" => $employee->id ?? '',
                "employee_no" => $employee->employee_no ?? '',
                "full_name" => $employee->full_name ?? '',
                "date_of_birth" => \Carbon\Carbon::parse($employee->date_of_birth)->format('M d, Y') ?? '',
                "date_of_joining" => \Carbon\Carbon::parse($employee->employeeAppointment->joining_date ?? '')->format('M d, Y') ?? '',
                "cnic" => $employee->cnic ?? '',
                "designation" => $employee->designation ?? '',
                "blood_group" => $employee->hrBloodGroup->name ?? '',
                "age" => \Carbon\Carbon::parse($employee->date_of_birth)->diff(\Carbon\Carbon::now())->format('%y years, %m months and %d days'),
                "picture" => $employee->picture,
                "mobile" => $employee->hrContactMobile->mobile ?? '',
                "salary" => $employee->currentSalary ?? '',
                "status" => $employee->hr_status_id ?? ''
            );
        }
        Cache::put('employeeList', $employees, now()->addHour(2));
        return response()->json($employees);
    }
}
