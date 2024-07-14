<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\EmployeeAppointment;
use App\Models\Hr\HrSalary;
use App\Models\Hr\HrDesignation;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\EmployeeDesignation;
use App\Models\Hr\EmployeeGrade;
use App\Models\Hr\EmployeeProject;
use App\Models\Hr\EmployeeDepartment;
use App\Models\Hr\EmployeeCategory;
use App\Models\Hr\EmployeeOffice;
use App\Models\Hr\EmployeeSalary;
use App\Models\Hr\EmployeeManager;
use App\Models\Hr\HrDepartment;
use App\Models\Hr\HrLetterType;
use App\Models\Project\PrDetail;
use App\Models\Common\Office;
use App\Models\Hr\HrGrade;
use App\Models\Hr\HrCategory;
use App\Models\Hr\HrEmployeeType;
use App\Http\Requests\Hr\AppointmentStore;
use App\Models\Hr\EmployeeContract;
use DB;

class AppointmentController extends Controller
{
    
    public function getData(){
        $salaries = DB::table('hr_salaries')->select('id','total_salary')->get();
        $designations = DB::table('hr_designations')->select('id','name')->get();
        $departments = DB::table('hr_departments')->select('id','name')->get();
        $categories = DB::table('hr_categories')->select('id','name')->get();
        $managers = DB::table('hr_employees')
        ->join('employee_designations','hr_employees.id', '=', 'employee_designations.hr_employee_id')
        ->join('hr_designations', function($join){
            $join->on('hr_designations.id', '=', 'employee_designations.hr_designation_id')
            ->where('hr_designations.level','<',7);
        }) 
        ->select('hr_employees.id','hr_employees.first_name','hr_employees.last_name','hr_employees.employee_no','hr_designations.name as designation')->groupBy('employee_designations.hr_employee_id')->orderBy('employee_designations.effective_date','DESC')->get();
        $letterTypes = DB::table('hr_letter_types')->select('id','name')->get();
        $projects = DB::table('pr_details')->select('id','name','project_no')->get();
        $offices = DB::table('offices')->select('id','name')->get();
        $grades = DB::table('hr_grades')->select('id','name')->get();
        $employeeTypes = DB::table('hr_employee_types')->select('id','name')->get();
        return response()->json(['salaries'=>$salaries,'designations'=>$designations,'departments'=>$departments,'categories'=>$categories,'managers'=>$managers,'letterTypes'=>$letterTypes,'projects'=>$projects, 'offices'=>$offices,'grades'=>$grades,'employeeTypes'=>$employeeTypes]);

    }
    
    
    public function edit(Request $request, $id)
    {

        // $salaries = HrSalary::all();
        //$designations = HrDesignation::all();
        // $employees = HrEmployee::all();
        // $departments = HrDepartment::all();
        // $letterTypes = HrLetterType::all();
        // $projects = PrDetail::all();
        // $offices = Office::all();
        // $hrGrades = HrGrade::all();
        // $hrCategories = HrCategory::all();
        // $hrEmployeeTypes = HrEmployeeType::all();

        // $data = EmployeeAppointment::where('hr_employee_id', session('hr_employee_id'))
        //     ->first();
        $hrEmployee = HrEmployee::find($id);

        if ($request->ajax()) {
            $view =  view('hr.appointment.edit', compact('hrEmployee'))->render();
            return response()->json($view);
           
            // $view =  view('hr.appointment.edit', compact('data', 'salaries', 'designations', 'employees', 'departments', 'letterTypes', 'projects', 'offices', 'hrGrades', 'hrCategories', 'hrEmployeeTypes'))->render();
            // return response()->json($view);
        } else {
            return back()->withError('Please contact to administrator, SSE_JS');
        }
    }


    public function update(AppointmentStore $request, $id)
    {
        //ensure client end is is not changed
        if ($id != session('hr_employee_id')) {
            return response()->json(['status' => 'Not OK', 'message' => "Security Breach. No Data Change "]);
        }

        $input = $request->all();
        if ($request->filled('joining_date')) {
            $input['joining_date'] = \Carbon\Carbon::parse($request->joining_date)->format('Y-m-d');
        }
        if ($request->filled('expiry_date')) {
            $input['expiry_date'] = \Carbon\Carbon::parse($request->expiry_date)->format('Y-m-d');
        }
        $input['hr_employee_id'] = session('hr_employee_id');
        $input['effective_date'] = $input['joining_date'];

        $appointment = EmployeeAppointment::where('hr_employee_id', $id)->first();
        $employeeManager = EmployeeManager::where('hr_employee_id', $id)->first();
        $employeeDepartment = EmployeeDepartment::where('hr_employee_id', $id)->first();
        $employeeDesignation = EmployeeDesignation::where('hr_employee_id', $id)->first();
        $employeeSalary = EmployeeSalary::where('hr_employee_id', $id)->first();
        $employeeOffice = EmployeeOffice::where('hr_employee_id', $id)->first();
        $employeeCategory = EmployeeCategory::where('hr_employee_id', $id)->first();
        $employeeProject = EmployeeProject::where('hr_employee_id', $id)->first();
        $employeeGrade = EmployeeGrade::where('hr_employee_id', $id)->first();

        DB::transaction(function () use ($input, $request, $appointment, $employeeManager, $employeeDesignation, $employeeDepartment, $employeeSalary, $employeeOffice, $employeeCategory, $employeeProject, $employeeGrade) {
            //check if appointment exist then update else create

            EmployeeAppointment::updateOrCreate(
                ['id' => $appointment->id ?? ''],
                $input
            );

            EmployeeManager::updateOrCreate(
                ['id' => $employeeManager->id ?? ''],
                $input
            );

            EmployeeDepartment::updateOrCreate(
                ['id' => $employeeDepartment->id ?? ''],
                $input
            );

            EmployeeDesignation::updateOrCreate(
                ['id' => $employeeDesignation->id ?? ''],
                $input
            );

            EmployeeSalary::updateOrCreate(
                ['id' => $employeeSalary->id ?? ''],
                $input
            );

            EmployeeOffice::updateOrCreate(
                ['id' => $employeeOffice->id ?? ''],
                $input
            );

            EmployeeCategory::updateOrCreate(
                ['id' => $employeeCategory->id ?? ''],
                $input
            );

            EmployeeProject::updateOrCreate(
                ['id' => $employeeProject->id ?? ''],
                $input
            );
            if ($request->filled('hr_grade_id')) {
                EmployeeGrade::updateOrCreate(
                    ['id' => $employeeGrade->id ?? ''],
                    $input
                );
            }
            if ($request->filled('expiry_date')) {
                $data = EmployeeContract::where('hr_employee_id', session('hr_employee_id'))->orderBy('to', 'desc')->first();
                if (empty($data) ||  $data->to < $input['expiry_date']) {
                    EmployeeContract::Create(
                        [
                            'from' => $input['joining_date'],
                            'to' => $input['expiry_date'],
                            'hr_employee_id' => $input['hr_employee_id']
                        ]
                    );
                }
            }
        }); // end transcation

        return response()->json(['status' => 'OK', 'message' => "Data Successfully Update"]);
    }
}
