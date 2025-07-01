<?php

namespace App\Http\Controllers\HR;

use DB;
use DataTables;
use App\Models\Hr\HrReport;
use App\Models\Hr\HrStatus;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDepartment;
use App\Models\HrDocumentation;
use App\Models\Common\Education;
use App\Models\Hr\HrDesignation;
use App\Models\Hr\HrDocumentName;
use App\Http\Controllers\Controller;
use App\Models\Hr\HrEmployeeCompany;



class HrReportsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = HrReport::orderBy('order', 'asc')->get()->map(function ($report) {
                // Convert route name to URL
                try {
                    $report->full_url = route($report->route);
                } catch (\Exception $e) {
                    // Fallback to URL if route name doesn't exist
                    $report->full_url = url($report->route);
                }
                return $report;
            });
            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('hr-reports-edit') && auth()->user()->can('hr-reports-delete')) {
                        $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-info btn-sm editReport">Edit</a> ';
                        $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-sm deleteReport">Delete</a>';
                    } else if (auth()->user()->can('hr-reports-edit')) {
                        $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-info btn-sm editReport">Edit</a> ';
                    } else if (auth()->user()->can('hr-reports-delete')) {
                        $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger btn-sm deleteReport">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create()
    {
        return view('hr.reports.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'route' => 'required|string|max:255|unique:hr_reports,route,' . $request->report_id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'integer|unique:hr_reports,order,' . $request->report_id,
        ]);

        HrReport::updateOrCreate(
            ['id' => $request->report_id],
            $request->all()
        );

        return response()->json(['message' => 'Report saved successfully.']);
    }

    public function edit($id)
    {
        $report = HrReport::find($id);
        return response()->json($report);
    }

    public function destroy($id)
    {
        HrReport::find($id)->delete();
        return response()->json(['message' => 'Report deleted successfully.']);
    }
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

    public function shortProfile()
    {
        $employees = HrEmployee::where('hr_status_id', 1)->with('employeeCurrentDesignation')->get();

        return view('hr.reports.shortProfile', compact('employees'));
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


                ->addColumn('full_name', function ($row) {
                    return $row->first_name . '' .  $row->last_name;
                })
                ->addColumn('designation', function ($row) {
                    return $row->employeeCurrentDesignation->name ?? '';
                })
                ->addColumn('project', function ($row) {

                    $project = $row->employeeCurrentProject->name ?? 'N/A';
                    if ($project == 'overhead') {
                        $project = $row->employeeCurrentOffice->name ?? 'N/A';
                    }
                    return $project = strlen($project) > 30 ? substr($project, 0, 30) . '...' : $project;
                    //return $row->employeeCurrentProject->name ?? '';
                })
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

    public function employee_list(Request $request)
    {
        if ($request->ajax()) {
            $query = HrEmployee::with([
                'degreeYearAbove12',
                'degreeAbove12',
                'hrEducation',
                'hrDepartment',
                'hrContactMobile',
                'employeeAppointment',
                'hrMembership',
                'hrBloodGroup',
                'hrContactLandline',
                'hrContactEmail',
                'hrEmergency',
                'employeeCurrentDesignation',
                'employeeCurrentSalary'
            ]);

            // Apply filters
            if ($request->filled('employee_name')) {
                $name = $request->employee_name;
                $query->where(function ($q) use ($name) {
                    $q->where('first_name', 'like', "%{$name}%")
                        ->orWhere('last_name', 'like', "%{$name}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$name}%"]);
                });
            }

            if ($request->has('employee_no') && $request->employee_no != '') {
                $query->where('employee_no', 'like', '%' . $request->employee_no . '%');
            }

            if ($request->has('status') && $request->status != '') {
                $query->where('hr_status_id', $request->status);
            }
            // Multiple departments filter (OR condition)
            if ($request->filled('department')) {
                $query->where(function ($q) use ($request) {
                    foreach ($request->department as $department) {
                        $q->orWhereHas('employeeCurrentDepartment', function ($q) use ($department) {
                            $q->where('hr_departments.id', $department);
                        });
                    }
                });
            }

            // Multiple designations filter (OR condition)
            if ($request->filled('designation')) {
                $query->where(function ($q) use ($request) {
                    foreach ($request->designation as $designation) {
                        $q->orWhereHas('employeeCurrentDesignation', function ($q) use ($designation) {
                            $q->where('hr_designations.id', $designation);
                        });
                    }
                });
            }

            // Multiple education filter (OR condition)
            if ($request->filled('education')) {
                $query->where(function ($q) use ($request) {
                    foreach ($request->education as $education) {
                        $q->orWhereHas('hrEducation', function ($q) use ($education) {
                            $q->where('hr_educations.education_id', $education);
                        });
                    }
                });
            }


            // Add other filters as needed...

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('sr_no', function ($row) {
                    static $i = 0;
                    return ++$i;
                })
                ->addColumn('employee_name', function ($row) {
                    return $row->first_name . ' ' . $row->last_name;
                })
                ->addColumn('designation', function ($row) {
                    return $row->employeeCurrentDesignation->name ?? '';
                })
                ->addColumn('current_salary', function ($row) {
                    return addComma($row->employeeCurrentSalary->total_salary ?? '');
                })
                ->addColumn('department', function ($row) {
                    return $row->hrDepartment->name ?? '';
                })
                ->addColumn('joining_date', function ($row) {
                    return date('d-M-Y', strtotime($row->employeeAppointment->joining_date ?? ''));
                })
                ->addColumn('education', function ($row) {
                    $degrees = [];
                    // Combine degree names and years
                    foreach ($row->degreeAbove12 as $degree) {
                        $year = $row->degreeYearAbove12->firstWhere('education_id', $degree->id)->to ?? '';
                        $degrees[] = $degree->degree_name . ($year ? ' (' . $year . ')' : '');
                    }
                    return  implode(' + ', $degrees);
                })
                ->addColumn('pec_no', function ($row) {
                    return $row->hrMembership->membership_no ?? '';
                })
                ->addColumn('expiry_date', function ($row) {
                    return $row->hrMembership->expiry ?? '';
                })
                ->addColumn('mobile', function ($row) {
                    return $row->hrContactMobile->mobile ?? '';
                })
                ->addColumn('landline', function ($row) {
                    return $row->hrContactLandline->landline ?? '';
                })
                ->addColumn('email', function ($row) {
                    return $row->hrContactEmail->email ?? '';
                })
                ->addColumn('emergency_number', function ($row) {
                    return $row->hrEmergency->mobile ?? '';
                })
                ->addColumn('type', function ($row) {
                    return employeeType($row->employeeAppointment->hr_employee_type_id ?? 4);
                })
                ->addColumn('status', function ($row) {
                    return $row->hr_status_id ?? '';
                })
                ->addColumn('blood_group', function ($row) {
                    return $row->hrBloodGroup->name ?? '';
                })
                ->make(true);
        }
        $departments = HrDepartment::pluck('name', 'id');
        $statuses = HrStatus::pluck('name', 'id');
        $designations = HrDesignation::pluck('name', 'id');
        $educations = Education::orderBy('id', 'asc')->pluck('degree_name', 'id');
        // $designations = HrDesignation::pluck('name', 'id');
        // $designations = employeeDesignationArray();
        return view('hr.reports.employee_list', compact('departments', 'statuses', 'designations', 'educations'));
    }

    // public function employee_list(Request $request)
    // {
    //     $query = HrEmployee::with([
    //         'degreeYearAbove16',
    //         'degreeYearAbove12',
    //         'degreeAbove12',
    //         'hrDepartment',
    //         'hrContactMobile',
    //         'employeeAppointment',
    //         'hrMembership',
    //         'hrBloodGroup',
    //         'hrContactLandline',
    //         'hrContactEmail',
    //         'hrEmergency',
    //         'hrContactPermanent',
    //         'hrContactPermanentCity',
    //         'employeeCurrentDesignation',
    //         'employeeCurrentSalary'
    //     ]);

    //     // Apply search filters
    //     if ($request->has('employee_name') && $request->employee_name != '') {
    //         $query->where(function ($q) use ($request) {
    //             $q->where('first_name', 'like', '%' . $request->employee_name . '%')
    //                 ->orWhere('last_name', 'like', '%' . $request->employee_name . '%');
    //         });
    //     }

    //     if ($request->has('designation') && $request->designation != '') {
    //         $query->whereHas('employeeCurrentDesignation', function ($q) use ($request) {
    //             $q->where('name', 'like', '%' . $request->designation . '%');
    //         });
    //     }

    //     if ($request->has('department') && $request->department != '') {
    //         $query->whereHas('employeeCurrentDepartment', function ($q) use ($request) {
    //             $q->where('hr_departments.id', $request->department);
    //         });
    //     }

    //     if ($request->has('employee_no') && $request->employee_no != '') {
    //         $query->where('employee_no', 'like', '%' . $request->employee_no . '%');
    //     }

    //     if ($request->has('status') && $request->status != '') {
    //         $query->where('hr_status_id', $request->status);
    //     }

    //     // Get designation sorting array
    //     $designations = employeeDesignationArray();


    //     $allEmployees = $query->get();

    //     // Sort the collection
    //     $sortedEmployees = $allEmployees->sort(function ($a, $b) use ($designations) {
    //         $pos_a = array_search($a->employeeCurrentDesignation->name ?? '', $designations);
    //         $pos_b = array_search($b->employeeCurrentDesignation->name ?? '', $designations);
    //         return $pos_a !== false ? $pos_a - $pos_b : 999999;
    //     });

    //     // Create paginator manually

    //     $employees = $query->paginate(25);
    //     // For AJAX requests
    //     if ($request->ajax()) {
    //         return view('hr.reports.partials.employee_table', compact('employees'))->render();
    //     }

    //     $departments = HrDepartment::pluck('name', 'id');
    //     $statuses = HrStatus::pluck('name', 'id');

    //     return view('hr.reports.employee_list', compact('employees', 'departments', 'statuses'));
    // }



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
