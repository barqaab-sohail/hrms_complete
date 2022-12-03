<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Common\Education;
use DB;

class HrReportsController extends Controller
{

    public function list()
    {

        return view('hr.reports.list');
    }


    public function cnicExpiryList()
    {


        $date = \Carbon\Carbon::now()->addDays(10)->format('Y-m-d');


        $employees = HrEmployee::where('cnic_expiry', '<', $date)->where('hr_status_id', 1)->get();


        return view('hr.reports.cnicExpiryList', compact('employees'));
    }

    public function missingDocumentList()
    {

        $employees = HrEmployee::where('hr_status_id', 1)->with('employeeProject', 'appointmentLetter', 'cnicFront', 'hrForm', 'joiningReport', 'engineeringDegree', 'educationalDocuments')->get();

        return view('hr.reports.missingDocumentList', compact('employees'));
    }

    public function searchEmployee()
    {

        //$employees = HrEmployee::where('hr_status_id',1)->with('engineeringDegree')->get();

        // $employees = DB::table('hr_employees')
        //             ->join('hr_document_name_hr_documentation','hr_employee_id','=','hr_employees.id')
        //             ->join('hr_educations','hr_educations.hr_employee_id','=','hr_employees.id')
        //             ->join('educations','educations.id','=','hr_educations.education_id')
        //             ->where('hr_document_name_id', 6)->get();
        //            // ->where('email', $request->email)->where('cnic',$request->cnic)->first();

        //$employees = $employees->unique('hr_employee_id');
        $degrees = Education::all();
        return view('hr.reports.searchEmployee.search', compact('degrees'));
    }

    public function report_1()
    {
        $employees = HrEmployee::where('hr_status_id', 1)->with('degreeYearAbove12', 'degreeAbove12', 'hrDepartment', 'hrContactMobile', 'employeeAppointment', 'hrMembership', 'hrBloodGroup', 'hrContactLandline', 'hrEmergency', 'hrContactPermanent', 'hrContactPermanentCity', 'employeeCurrentDesignation', 'employeeCurrentSalary')->get();

        $designations = employeeDesignationArray();

        $employees = $employees->sort(function ($a, $b) use ($designations) {
            $pos_a = array_search($a->employeeCurrentDesignation->name ?? '', $designations);
            $pos_b = array_search($b->employeeCurrentDesignation->name ?? '', $designations);
            return $pos_a !== false ? $pos_a - $pos_b : 999999;
        });

        return view('hr.reports.report_1', compact('employees'));
    }

    public function searchEmployeeResult(Request $request)
    {

        $data = $request->all();

        if ($request->filled('date_of_birth')) {
            $data['date_of_birth'] = \Carbon\Carbon::parse($request->date_of_birth)->format('Y-m-d');
        }

        $result = HrEmployee::join('hr_educations', 'hr_educations.hr_employee_id', '=', 'hr_employees.id')

            ->when($data['date_of_birth'], function ($query) use ($data) {
                return $query->where('date_of_birth', '>=', $data['date_of_birth']);
            })
            ->when($data['degree'], function ($query) use ($data) {
                return $query->where('education_id', $data['degree']);
            })
            ->select('hr_employees.*')
            ->distinct('id')
            ->get();

        return view('hr.reports.searchEmployee.result', compact('result'));
    }
}
