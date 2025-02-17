<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Notifications\UpdateRecordNotification;
use App\Models\Common\Gender;
use App\Models\Common\MaritalStatus;
use App\Models\Common\Religion;
use App\Models\Common\BloodGroup;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDesignation;
use App\Models\Hr\HrCategory;
use App\Models\Hr\EmployeeManager;
use App\Models\Hr\HrStatus;
use App\Models\Common\Education;
use App\Models\Project\PrDetail;
use App\Models\Hr\HrDocumentation;
use App\Models\Hr\HrEmployeeHusband;
use App\Models\Common\Office;
use DB;
use App\Http\Requests\Hr\EmployeeStore;
use DataTables;
use App\User;
use Cache;
use PDF;

class EmployeeController extends Controller
{

    public function getEmployees()
    {

        // cache data 

        if (Cache::has('employees')) {
            $data = Cache::get('employees');
            return $data;
        }

        $data = HrEmployee::with('employeeCurrentProject', 'employeeCurrentDesignation', 'employeeCurrentOffice', 'hrExit', 'employeeAppointment', 'hrContactMobile', 'hrBloodGroup', 'employeeCurrentSalary', 'salayEffectiveDate')->get();

        $data = $this->employeeSortData($data);

        foreach ($data as $employee) {

            $lastWorkingDate = '';
            if ($employee->hr_status_id == "Active") {
                $lastWorkingDate = '';
            } else {
                $lastWorkingDate = $employee->last_working_date ?? '';
            }

            $delete = '';
            if (Auth::user()->hasPermissionTo('hr delete record') || Auth::user()->hasRole('Super Admin')) {
                $delete = '<form  id="formDeleteContact' . $employee->id . '"  action="' . route('employee.destroy', $employee->id) . '" method="POST">' . method_field('DELETE') . csrf_field() . '
                                 <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you Sure to Delete\')" href= data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-trash-alt"></i></button>
                                 </form>';
            }

            $project = $employee->employeeCurrentProject->name ?? '';

            if ($project == 'overhead') {
                $project = $employee->employeeCurrentOffice?->name ?? '';
            }

            $fullName = '';
            if (Auth::user()->can('hr edit record') || Auth::user()->can('hr view record')) {
                $fullName = '<a href="' . route('employee.edit', $employee->id) . '" style="color:grey">' . $employee->full_name . '</a>';
            } else {
                $fullName = $employee->full_name;
            }

            $effectiveDate = '';
            $salary = '';
            if ($employee->employeeCurrentSalary) {
                $salary =  number_format($employee->employeeCurrentSalary->total_salary);
                $effectiveDate = \Carbon\Carbon::parse($employee->salayEffectiveDate->effective_date)->format('M d, Y');
            }
            $joiningDate = '';
            if ($employee->employeeAppointment?->joining_date) {
                $joiningDate = \Carbon\Carbon::parse($employee->employeeAppointment->joining_date)->format('M d, Y');
            }

            $employees[] =  array(
                "id" => $employee->id ?? '',
                "employee_no" => $employee->employee_no ?? '',
                "full_name" =>  $fullName,
                "date_of_birth" => \Carbon\Carbon::parse($employee->date_of_birth)->format('M d, Y') ?? '',
                "joining_date" =>   $joiningDate,
                "cnic" => $employee->cnic ?? '',
                "designation" => $employee->designation ?? '',
                "blood_group" => $employee->hrBloodGroup->name ?? '',
                "age" => \Carbon\Carbon::parse($employee->date_of_birth)->diff(\Carbon\Carbon::now())->format('%y years, %m months and %d days'),
                "mobile" => $employee->hrContactMobile->mobile ?? '',
                "salary" => $salary,
                'effective_date' => $effectiveDate,
                "project" =>  $project,
                "delete" =>  $delete,
                "last_working_date" =>  $lastWorkingDate,
                "expiry_date" =>   $employee->employeeAppointment->expiry_date ?? '',
                "hr_status_id" => $employee->hr_status_id ?? ''
            );
        }
        Cache::put('employees', $employees, now()->addHour(12));
        return $employees;
    }

    public function refresh()
    {
        \Artisan::call('cache:clear');
        $value = $this->getEmployees();
        $value = collect($value);
        return DataTables::of($value)->rawColumns(['full_name', 'project', 'delete'])->toJson();
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {

            $value = $this->getEmployees();
            $value = collect($value);
            return DataTables::of($value)->rawColumns(['full_name', 'delete'])->toJson();
        }

        return view('hr.employee.listDataTable');
    }

    public function employeeSortData($employees)
    {
        $first = array('1000124', '1000274', '1000110', '1000001', '1000151', '1000182', '1000155', '1000160', '1000139', '1000145', '1000147', '1000173', '1000174', '1000181', '1000171', '1000040');
        $second = range(1000001, 1099999);
        $employeeNos = array_merge($first,  $second);

        $employees =  $employees->sortBy(function ($model) use ($employeeNos) {
            return array_search($model->employee_no, $employeeNos);
        });

        //   // second sort with respect to Hr Status
        $hrStatuses = array('On Board', 'Resigned', 'Terminated', 'Retired', 'Long Leave', 'ManMonth Ended', 'Death');

        $employees = $employees->sort(function ($a, $b) use ($hrStatuses) {
            $pos_a = array_search($a->hr_status_id ?? '', $hrStatuses);
            $pos_b = array_search($b->hr_status_id ?? '', $hrStatuses);
            return $pos_a - $pos_b;
        });

        return $employees;
    }

    public function create()
    {

        session()->put('hr_employee_id', '');
        $genders = Gender::all();
        $maritalStatuses = MaritalStatus::all();
        $religions = Religion::all();

        // check variable size
        // $data = HrEmployee::where('hr_status_id',1)->take(50)->get();
        // $serializedFoo = serialize($data);
        // $size = mb_strlen($serializedFoo, '8bit');
        // dd($size);

        return view('hr.employee.create', compact('genders', 'maritalStatuses', 'religions'));
    }

    public function card()
    {
        $data = ['title' => 'Welcome to NiceSnippets.com'];
        $pdf = PDF::loadView('hr/employee/employeeCard', $data);
        return $pdf->download('EmployeeCard.pdf');
    }


    public function store(EmployeeStore $request)
    {

        $input = $request->all();
        if ($request->filled('date_of_birth')) {
            $input['date_of_birth'] = \Carbon\Carbon::parse($request->date_of_birth)->format('Y-m-d');
        }
        if ($request->filled('cnic_expiry')) {
            $input['cnic_expiry'] = \Carbon\Carbon::parse($request->cnic_expiry)->format('Y-m-d');
        }

        $input['hr_status_id'] = HrStatus::where('name', 'On Board')->first()->id;
        $input['employee_no'] = generateEmployeeId();

        $employee = '';
        DB::transaction(function () use ($input, &$employee) {

            $employee = HrEmployee::create($input);

            if ($input['husband_name']) {
                HrEmployeeHusband::create([
                    'hr_employee_id' => $employee->id,
                    'husband_name' => $input['husband_name']
                ]);
            }
        }); // end transcation


        //remove cache
        Cache::forget('employees');

        return response()->json(['url' => route("employee.edit", $employee), 'message' => 'Data Successfully Saved']);
    }


    public function activeEmployeesList()
    {

        $employees = HrEmployee::where('hr_status_id', 1)->with('employeeCurrentDesignation', 'employeeCurrentProject', 'signedAppointmentLetter', 'employeeCategory', 'hod', 'hrContactMobile', 'employeeAppointment')->get();

        return view('hr.employee.activeEmployeesList', compact('employees'));
    }

    public function allEmployeeList()
    {
        $employees = HrEmployee::all();
        return view('hr.employee.allEmployeeList', compact('employees'));
    }



    public function missingDocuments()
    {

        $employees = HrEmployee::with('documentName')->get();

        return view('hr.employee.missingDocuments', compact('employees'));
    }

    public function edit(Request $request, $id)
    {
        $users = [1, 14, 17, 20, 21, 25, 64];
        if (in_array($id, managementEmployeeIds()) && !in_array(auth()->user()->id, $users)) {
            abort(403, 'Your are not authorized');
        }

        $genders = Gender::all();
        $maritalStatuses = MaritalStatus::all();
        $religions = Religion::all();
        $data = HrEmployee::with('hrEmployeeHusband')->find($id);
        session()->put('hr_employee_id', $data->id);

        if ($request->ajax()) {
            return view('hr.employee.ajax', compact('genders', 'maritalStatuses', 'religions', 'data', 'id'));
        } else {
            return view('hr.employee.edit', compact('genders', 'maritalStatuses', 'religions', 'data', 'id'));
        }
    }


    public function update(EmployeeStore $request, $id)
    {


        // //ensure client end is is not changed
        // if ($id != session('hr_employee_id')) {
        //     return response()->json(['status' => 'Not OK', 'message' => "Security Breach. No Data Change "]);
        // }

        $input = $request->all();
        if ($request->filled('date_of_birth')) {
            $input['date_of_birth'] = \Carbon\Carbon::parse($request->date_of_birth)->format('Y-m-d');
        }
        if ($request->filled('cnic_expiry')) {
            $input['cnic_expiry'] = \Carbon\Carbon::parse($request->cnic_expiry)->format('Y-m-d');
        }

        $oldData = HrEmployee::find($id);

        DB::transaction(function () use ($input, $id) {
            HrEmployee::findOrFail($id)->update($input);

            if ($input['husband_name']) {
                HrEmployeeHusband::updateOrCreate(['hr_employee_id' => $id], $input);
            } else {
                HrEmployeeHusband::where('hr_employee_id', $id)->delete();
            }
        }); // end transcation

        if ($request->ajax()) {
            return response()->json(['status' => 'OK', 'message' => "Data Successfully Updated"]);
        } else {
            return back()->with('message', 'Data Successfully Updated');
        }
    }

    public function destroy($id)
    {

        //  HrEmployee::findOrFail($id)->delete();

        return back()->with('message', 'Data Successfully Deleted');
        //return response()->json(['status'=> 'OK', 'message' => 'Data Successfully Deleted']);
    }

    public function employeeCnic(Request $request)
    {

        if ($request->get('query')) {
            $hrEmployee = HrEmployee::where('cnic', $request->get('query'))->get()->first();

            if ($hrEmployee) {

                return response()->json(['status' => 'Not Ok']);
            } else {
                return response()->json(['status' => 'Ok']);
            }
        }
    }

    public function userData($id)
    {

        $genders = Gender::all();
        $maritalStatuses = MaritalStatus::all();
        $religions = Religion::all();
        $data = HrEmployee::find($id);
        session()->put('hr_employee_id', $data->id);


        return view('hr.employee.edit', compact('genders', 'maritalStatuses', 'religions', 'data'));
    }

    public function search()
    {

        $categories = HrCategory::all();
        $bloodGroups = BloodGroup::all();
        $degrees = Education::all();
        $designations = HrDesignation::all();
        $projects = PrDetail::select('id', 'name', 'project_no')->get();
        $offices = Office::select('id', 'name')->where('is_active', 1)->get();

        $managerIds = EmployeeManager::all()->pluck('hr_manager_id')->toArray();
        $managers = HrEmployee::where('hr_status_id', 1)->wherein('id', $managerIds)->with('employeeDesignation')->get(['id', 'first_name', 'last_name']);

        $employees = HrEmployee::select('id', 'first_name', 'last_name')->with('employeeDesignation')->get();

        return view('hr.employee.search.search', compact('categories', 'offices', 'degrees', 'designations', 'projects', 'managers', 'employees', 'bloodGroups'));
    }


    public function result(Request $request)
    {

        $data = $request->all();

        if ($request->filled('document_name')) {
            $result = HrDocumentation::with('hrEmployee')
                ->when($data['document_name'], function ($query) use ($data) {
                    return $query->where('description', 'LIKE', "%{$data['document_name']}%");
                })
                ->when($data['employee'], function ($query) use ($data) {
                    return $query->join('hr_employees', 'hr_employees.id', '=', 'hr_documentations.hr_employee_id')
                        ->where('hr_employees.id', $data['employee']);
                })
                ->when($data['project'], function ($query) use ($data) {
                    return $query->join('employee_projects', 'employee_projects.hr_employee_id', '=', 'hr_documentations.hr_employee_id')->orderBy('employee_projects.effective_date', 'desc')
                        ->where('pr_detail_id', '=', $data['project'])->groupBy('hr_documentations.hr_employee_id');
                })
                ->when($data['blood_group'], function ($query) use ($data) {
                    return $query->join('hr_blood_groups', 'hr_blood_groups.hr_employee_id', '=', 'hr_documentations.hr_employee_id')
                        ->where('blood_group_id', '=', $data['blood_group']);
                })
                ->when($data['category'], function ($query) use ($data) {
                    return  $query->join('employee_categories', 'employee_categories.hr_employee_id', '=', 'hr_documentations.hr_employee_id')->orderBy('employee_categories.effective_date', 'desc')->where('hr_category_id', $data['category'])->groupBy('hr_documentations.hr_employee_id');
                })
                ->when($data['designation'], function ($query) use ($data) {
                    return  $query->join('employee_designations', 'employee_designations.hr_employee_id', '=', 'hr_documentations.hr_employee_id')->orderBy('employee_designations.effective_date', 'desc')->where('hr_designation_id', $data['designation']);
                })
                ->when($data['degree'], function ($query) use ($data) {
                    return $query->join('hr_educations', 'hr_educations.hr_employee_id', 'hr_documentations.hr_employee_id')->where('education_id', '=', $data['degree']);
                })
                ->when($data['manager'], function ($query) use ($data) {
                    return   $query->join('employee_managers', 'employee_managers.hr_employee_id', '=', 'hr_documentations.hr_employee_id')->orderBy('employee_managers.effective_date', 'desc')->where('hr_manager_id', $data['manager']);
                })
                ->get();
            //this variable used only for goting to else part of result blade.
            $documents = true;
            return view('hr.employee.search.result', compact('result', 'documents'));
        } elseif ($request->filled('education_year')) {

            $documents = false;
            $year = $data['education_year'];


            $employeeIds =  DB::table('hr_employees')
                ->join('hr_educations', 'hr_employees.id', '=', 'hr_educations.hr_employee_id')
                ->join('educations', 'educations.id', '=', 'hr_educations.education_id')
                ->where('educations.level', '>=', $year)->select('hr_employees.*')->distinct('hr_employees.employee_no')->whereIn('hr_employees.hr_status_id', [1, 2, 3, 4, 5, 6, 7])->pluck('id')->toArray();

            $result = HrEmployee::whereIn('id', $employeeIds)->get();

            return view('hr.employee.search.result', compact('result', 'documents'));
        } else {

            $documents = false;
            $result = HrEmployee::whereIn('hr_status_id', [1, 2, 3, 4, 5, 6, 7])
                ->when($data['employee'], function ($query) use ($data) {
                    return $query->where('hr_employees.id', '=', $data['employee']);
                })
                ->when($data['degree'], function ($query) use ($data) {
                    return $query->join('hr_educations', 'hr_educations.hr_employee_id', 'hr_employees.id')->where('education_id', '=', $data['degree']);
                })
                ->when($data['blood_group'], function ($query) use ($data) {
                    return $query->join('hr_blood_groups', 'hr_blood_groups.hr_employee_id', '=', 'hr_employees.id')
                        ->where('blood_group_id', '=', $data['blood_group']);
                })
                ->when($data['project'], function ($query) use ($data) {
                    return $query->join('employee_projects', 'employee_projects.hr_employee_id', '=', 'hr_employees.id')
                        ->where('pr_detail_id', '=', $data['project']);
                })
                ->when($data['category'], function ($query) use ($data) {

                    $employees = currentActiveCategory($data['category']);
                    return     $query->whereIn('hr_employees.id', $employees);
                    //$query->join('employee_categories', 'employee_categories.hr_employee_id', '=', 'hr_employees.id')->orderBy('employee_categories.effective_date', 'desc')->groupBy('employee_categories.hr_employee_id')->where('employee_categories.hr_category_id', $data['category']);
                })
                ->when($data['designation'], function ($query) use ($data) {
                    return  $query->join('employee_designations', 'employee_designations.hr_employee_id', '=', 'hr_employees.id')->orderBy('employee_designations.effective_date', 'desc')->where('hr_designation_id', $data['designation']);
                })
                ->when($data['manager'], function ($query) use ($data) {
                    $employees = currentActiveSubordinates($data['manager']);
                    return     $query->whereIn('hr_employees.id', $employees);
                })
                ->when($data['office_id'], function ($query) use ($data) {
                    $employees = currentActiveOffice($data['office_id']);
                    return     $query->whereIn('hr_employees.id', $employees);
                })
                ->select('hr_employees.*')
                ->get();

            return view('hr.employee.search.result', compact('result', 'documents'));
        }


        if ($request->filled('category')) {
            $result = collect(HrEmployee::join('employee_categories', 'employee_categories.hr_employee_id', '=', 'hr_employees.id')->select('hr_employees.*', 'employee_categories.hr_category_id', 'employee_categories.effective_date as cat')->whereIn('hr_status_id', array(1, 5))->orderBy('cat', 'desc')->get());
            $resultUnique = ($result->unique('id'));
            $resultUnique->values()->all();
            $result = $resultUnique->where('hr_category_id', $request->category);
            return view('hr.employee.search.result', compact('result'));
        }

        if ($request->filled('designation')) {
            $result = collect(HrEmployee::join('employee_designations', 'employee_designations.hr_employee_id', '=', 'hr_employees.id')->select('hr_employees.*', 'employee_designations.hr_designation_id', 'employee_designations.effective_date')->where('hr_status_id', 1)->orderBy('effective_date', 'desc')->get());
            $resultUnique = ($result->unique('id'));
            $resultUnique->values()->all();
            $result = $resultUnique->where('hr_designation_id', $request->designation);
            return view('hr.employee.search.result', compact('result'));
        }

        if ($request->filled('degree')) {

            $data = $request->all();

            $result = HrEmployee::join('hr_educations', 'hr_educations.hr_employee_id', 'hr_employees.id')->select('hr_employees.*', 'hr_educations.education_id', 'hr_educations.to')
                ->when($data['degree'], function ($query) use ($data) {
                    return $query->where('education_id', '=', $data['degree']);
                })

                ->where('hr_status_id', 1)->get();

            return view('hr.employee.search.result', compact('result'));
        }

        if ($request->filled('project')) {

            $result = collect(HrEmployee::join('employee_projects', 'employee_projects.hr_employee_id', '=', 'hr_employees.id')->select('hr_employees.*', 'employee_projects.pr_detail_id', 'employee_projects.effective_date')->where('hr_status_id', 1)->orderBy('effective_date', 'desc')->get());
            $resultUnique = ($result->unique('id'));
            $resultUnique->values()->all();
            $result = $resultUnique->where('pr_detail_id', $request->project);
            return view('hr.employee.search.result', compact('result'));
        }

        if ($request->filled('manager')) {

            $result = collect(HrEmployee::join('employee_managers', 'employee_managers.hr_employee_id', '=', 'hr_employees.id')->select('hr_employees.*', 'employee_managers.hr_manager_id', 'employee_managers.effective_date')->where('hr_status_id', 1)->orderBy('effective_date', 'desc')->get());
            $resultUnique = ($result->unique('id'));
            $resultUnique->values()->all();
            $result = $resultUnique->where('hr_manager_id', $request->manager);


            return view('hr.employee.search.result', compact('result'));
        }

        if ($request->filled('blood_group')) {
            $result = HrEmployee::join('hr_blood_groups', 'hr_blood_groups.hr_employee_id', '=', 'hr_employees.id')->select('hr_employees.*', 'hr_blood_groups.blood_group_id')->where('hr_status_id', 1)->where('blood_group_id', $request->blood_group)->get();
            return view('hr.employee.search.result', compact('result'));
        }


        if ($request->filled('employee')) {
            $result = HrEmployee::where('id', $request->employee)->get();
            return view('hr.employee.search.result', compact('result'));
        }
    }
}
