<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDesignation;
use App\Models\Common\Education;
use App\Models\Hr\HrEmployeeCompany;
use DataTables;
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

        $employees = HrEmployee::where('hr_status_id', 1)->with('employeeProject', 'employeeCurrentDepartment', 'appointmentLetter', 'cnicFront', 'hrForm', 'joiningReport', 'engineeringDegree', 'hrContactMobile', 'educationalDocuments', 'picture', 'signedAppointmentLetter')->get();

        return view('hr.reports.missingDocumentList', compact('employees'));
    }

    public function examptEducationDocuments($designation)
    {

        $designations = ["Kitchen Helper", "Security Guard", "Office Helper", "Utility Person", "Record Keeper", "Driver", "Electrician", "Sanitary Worker Part Time", "Cook", "Sanitary Worker", "Naib Qasid", "ChowkidarWatchman", "Line Foreman", "Patwari", "Khalasi", "Sweeper Sanitary Worker", "Driver Cum Utility Person Part Time", "Part Time Gardner", "Sweeper", "Office Boy Cum Mali", "Recovery Officer", "Utility Person Part Time", "Field Helper", "Sweeper (Part Time)", "Chakbandi Coordinator", "Hastel Attended", "Office Helper", "Sanitary Worker Part Time", "Utility Person Cook"];

        // $hrDesignation = HrDesignation::where('name',$designation)->first();

        // if($hrDesignation->level>11){
        //     return true;
        // }else{
        //     return false;
        // }

        if (in_array($designation, $designations, true)) {
            return true;
        } else {
            return false;
        }
    }

    public function mmissingDocuments(Request $request)
    {

        $otherCompanyEmployeeIds = HrEmployeeCompany::where('partner_id', '!=', 1)->pluck('hr_employee_id')->toArray();


        if ($request->ajax()) {
            $data = HrEmployee::where('hr_status_id', 1)->whereNotIn('id', $otherCompanyEmployeeIds)->with('hrMembership', 'employeeProject', 'employeeCurrentDepartment', 'appointmentLetter', 'cnicFront', 'hrForm', 'joiningReport', 'engineeringDegree', 'hrContactMobile', 'educationalDocuments', 'picture', 'signedAppointmentLetter')->get();

            foreach ($data as $key => $employee) {

                $frontCNIC = $employee->cnicFront->first() ? '' : 'Missing';
                $signedAppointmentLetter = $employee->signedAppointmentLetter ? '' : 'Missing';
                $appointmentLetter = $employee->appointmentLetter->first() ? '' : 'Missing';
                $hrForm = $employee->hrForm->first() ? '' : 'Missing';
                $joiningReport = $employee->joiningReport->first() ? '' : 'Missing';
                $educationalDocuments = $employee->educationalDocuments->first() ? '' : 'Missing';



                if ($this->examptEducationDocuments($employee->designation)) {
                    $educationalDocuments = 'Not Required';
                }


                if ($employee->hrMembership->expiry ?? '') {
                    $engineeringDegree = $employee->engineeringDegree->first() ? '' : 'Missing';
                } else {
                    $engineeringDegree = 'Not Required';
                }

                if ($frontCNIC  != 'Missing' && $signedAppointmentLetter != 'Missing' &&  $appointmentLetter != 'Missing' &&  $hrForm != 'Missing' &&  $joiningReport != 'Missing' &&  $educationalDocuments != 'Missing' && $engineeringDegree != 'Missing') {
                    $data->forget($key);
                }
            }

            return  DataTables::of($data)
                ->addIndexColumn()



                ->addColumn('division', function ($row) {
                    return $row->employeeCurrentDepartment->name ?? '';
                })
                ->addColumn('front_cnic', function ($row) {
                    return $row->cnicFront->first() ? '' : 'Missing';
                })
                ->addColumn('picture', function ($row) {
                    return $row->picture ? '' : 'Missing';
                })
                ->addColumn('signed_appointment_letter', function ($row) {
                    return $row->signedAppointmentLetter?->first() ? '' : 'Missing';
                })
                ->addColumn('appointment_letter', function ($row) {
                    return $row->appointmentLetter?->first() ? '' : 'Missing';
                })
                ->addColumn('Hr_Form', function ($row) {
                    return $row->hrForm->first() ? '' : 'Missing';
                })
                ->addColumn('joining_report', function ($row) {
                    return $row->joiningReport->first() ? '' : 'Missing';
                })
                ->addColumn('engineer_degree', function ($row) {
                    if ($row->hrMembership->expiry ?? '') {
                        return $row->engineeringDegree->first() ? '' : 'Missing';
                    } else {
                        return '';
                    }
                })
                ->addColumn('education_documents', function ($row) {
                    return $row->educationalDocuments->first() ? '' : 'Missing';
                })
                ->addColumn('mobile', function ($row) {
                    return $row->hrContactMobile->mobile ?? '';
                })

                ->addColumn('Edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editExperience">Edit</a>';

                    return $btn;
                })
                ->addColumn('Delete', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteExperience">Delete</a>';

                    return $btn;
                })

                ->rawColumns(['Edit', 'Delete'])
                ->make(true);
        }

        return view('hr.reports.missingDocuments');
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
        $employees = HrEmployee::whereIn('hr_status_id', [1, 2, 3, 4, 5, 6])->with('degreeYearAbove16', 'degreeYearAbove16', 'degreeYearAbove12', 'degreeAbove12', 'hrDepartment', 'hrContactMobile', 'employeeAppointment', 'hrMembership', 'hrBloodGroup', 'hrContactLandline', 'hrEmergency', 'hrContactPermanent', 'hrContactPermanentCity', 'employeeCurrentDesignation', 'employeeCurrentSalary')->get();

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

    public function pictureList()
    {
        $employees = HrEmployee::all();
        return view('hr.reports.pictureList', compact('employees'));
    }
}
