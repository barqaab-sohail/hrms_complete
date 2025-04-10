<?php

namespace App\Http\Controllers\HR;

use DB;
use DataTables;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Common\Education;
use App\Models\Hr\HrDesignation;
use App\Models\Hr\HrDocumentName;
use App\Http\Controllers\Controller;
use App\Models\Hr\HrEmployeeCompany;
use App\Models\HrDocumentation;

class HrReportsController extends Controller
{



    function getEmployeesWithMissingDocuments()
    {



        $requiredDocumentTitles = [
            "CNIC Front",
            "Picture",
            "Signed Appointment Letter",
            "HR Form",
            "Joining Report",
            "Educational Documents",
        ];

        $pecSpecificDocumentTitles = [
            "PEC Front",
            "Engineering Degree BSc",
        ];

        $excludedDesignations = [
            "Kitchen Helper",
            "Security Guard",
            "Office Helper",
            "Utility Person",
            "Record Keeper",
            "Driver",
            "Electrician",
            "Sanitary Worker Part Time",
            "Cook",
            "Sanitary Worker",
            "Naib Qasid",
            "ChowkidarWatchman",
            "Line Foreman",
            "Patwari",
            "Khalasi",
            "Sweeper Sanitary Worker",
            "Driver Cum Utility Person Part Time",
            "Part Time Gardner",
            "Sweeper",
            "Office Boy Cum Mali",
            "Recovery Officer",
            "Utility Person Part Time",
            "Field Helper",
            "Sweeper (Part Time)",
            "Chakbandi Coordinator",
            "Hastel Attended",
            "Office Helper",
            "Sanitary Worker Part Time",
            "Utility Person Cook"
        ];

        // Fetch required + PEC document IDs
        $allRequiredDocumentTitles = array_merge($requiredDocumentTitles, $pecSpecificDocumentTitles);

        $allRequiredDocuments = HrDocumentName::whereIn('name', $allRequiredDocumentTitles)
            ->get()
            ->keyBy('name');

        $missingData = [];

        $otherCompanyEmployeeIds = HrEmployeeCompany::where('partner_id', '!=', 1)->pluck('hr_employee_id')->toArray();

        $employees = HrEmployee::with(['employeeCurrentDesignation', 'membership', 'hrContactMobile', 'employeeCurrentDepartment', 'employeeCurrentProject'])->whereNotIn('id', $otherCompanyEmployeeIds)
            ->where('hr_status_id', 1)
            ->get();

        foreach ($employees as $employee) {
            $submittedDocumentIds = DB::table('hr_document_name_hr_documentation')
                ->where('hr_employee_id', $employee->id)
                ->pluck('hr_document_name_id')
                ->toArray();

            $missingDocs = [];

            // Step 1: Check default required documents
            foreach ($requiredDocumentTitles as $docTitle) {
                if (
                    $docTitle === "Educational Documents" &&
                    in_array($employee->employeeCurrentDesignation->name ?? '', $excludedDesignations)
                ) {
                    continue;
                }

                $docId = $allRequiredDocuments[$docTitle]->id ?? null;

                if ($docId && !in_array($docId, $submittedDocumentIds)) {
                    $missingDocs[] = $docTitle;
                }
            }

            // Step 2: Check PEC specific documents if applicable
            if ($employee->membership?->name === "Pakistan Engineering Council") {
                foreach ($pecSpecificDocumentTitles as $docTitle) {
                    $docId = $allRequiredDocuments[$docTitle]->id ?? null;

                    if ($docId && !in_array($docId, $submittedDocumentIds)) {
                        $missingDocs[] = $docTitle;
                    }
                }
            }

            if (!empty($missingDocs)) {
                $project = '';
                if ($employee->employeeCurrentProject->name == 'overhead') {
                    $project = $employee->employeeCurrentOffice->name;
                } else {
                    $project = $employee->employeeCurrentProject->name ?? 'N/A';
                }
                $missingData[] = [
                    'employee_no' => $employee->employee_no,
                    'employee_name' => $employee->first_name . ' ' . $employee->last_name ?? 'N/A',
                    'designation' => $employee->employeeCurrentDesignation->name ?? 'N/A',
                    'contact_number' => $employee->hrContactMobile->mobile ?? 'N/A',
                    'division' => $employee->employeeCurrentDepartment->name ?? 'N/A',
                    'project' => $project,
                    'missing_documents' => $missingDocs,
                ];
            }
        }

        return $missingData;
    }


    public function missingDocumentsTable(Request $request)
    {

        $data = collect($this->getEmployeesWithMissingDocuments())->map(function ($item) {
            $item['missing_documents'] = array_values($item['missing_documents']); // make sure it's array
            return $item;
        });

        return DataTables::of($data)
            ->addColumn('missing_documents', function ($row) {
                return collect($row['missing_documents'])->map(function ($doc) use ($row) {
                    return "$doc, </br>";
                })->implode(' ');
            })
            ->rawColumns(['missing_documents'])
            ->make(true);
    }

    public function missingDocumentsView()
    {
        return view('hr.employee.newMissingDocuments');
    }




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

                $picture = '';
                if (str_contains($employee->picture, 'Massets/images/default.png')) {
                    $picture = 'Missing';
                } else {
                    $picture = '';
                }

                if ($this->examptEducationDocuments($employee->designation)) {
                    $educationalDocuments = 'Not Required';
                }


                if ($employee->hrMembership->expiry ?? '') {
                    $engineeringDegree = $employee->engineeringDegree->first() ? '' : 'Missing';
                } else {
                    $engineeringDegree = 'Not Required';
                }

                if ($frontCNIC  != 'Missing' && $signedAppointmentLetter != 'Missing' &&  $appointmentLetter != 'Missing' &&  $hrForm != 'Missing' &&  $joiningReport != 'Missing' &&  $educationalDocuments != 'Missing' && $engineeringDegree != 'Missing' && $picture != 'Missing') {
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

                    if (str_contains($row->picture, 'Massets/images/default.png')) {
                        return 'Missing';
                    } else {
                        return '';
                    }
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
