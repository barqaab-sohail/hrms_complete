<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrAppointment;
use App\Models\Hr\HrSalary;
use App\Models\Hr\HrDesignation;
use App\Models\Hr\EmployeeDesignation;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\EmployeeManager;
use App\Models\Hr\EmployeeProject;
use App\Models\Hr\HrDepartment;
use App\Models\Hr\HrLetterType;
use App\Models\Project\PrDetail;
use App\Models\Office\Office;
use App\Models\Hr\HrGrade;
use App\Models\Hr\HrCategory;
use App\Models\Hr\HrEmployeeType;
use App\Http\Requests\Hr\AppointmentStore;
use DB;

class AppointmentController extends Controller
{
    public function edit(Request $request, $id){

    	$salaries = HrSalary::all();
        $designations = HrDesignation::all();
    	$managers = HrEmployee::all();
    	$departments = HrDepartment::all();
    	$letterTypes = HrLetterType::all();
    	$projects = PrDetail::all();
        $offices = Office::all();
        $hrGrades = HrGrade::all();
        $hrCategories = HrCategory::all();
        $hrEmployeeTypes = HrEmployeeType::all();
    	$data = HrAppointment::where('hr_employee_id',session('hr_employee_id'))->first();
        $employeeProject = EmployeeProject::where('hr_employee_id',session('hr_employee_id'))->orderBy('created_at','asc')->first();
        
        // $employeeDesignation = HrAppointment::where('hr_employee_id', session('hr_employee_id'))
        //     ->join('hr_appointment_details', 'hr_appointments.id', '=', 'hr_appointment_details.hr_appointment_id')
        //     ->join('hr_designations', 'hr_designations.id', '=', 'hr_appointment_details.hr_designation_id')
        //     ->select('hr_designations.*')
        //     ->first();

        // $employeeHod = HrAppointment::where('hr_employee_id', session('hr_employee_id'))
        // ->join('hr_appointment_details', 'hr_appointments.id', '=', 'hr_appointment_details.hr_appointment_id')
        // ->join('hr_employees', 'hr_employees.id', '=', 'hr_appointment_details.hr_manager_id')
        // ->select('hr_employees.*')
        // ->first();

        //  $employeeDepartment = HrAppointment::where('hr_employee_id', session('hr_employee_id'))
        // ->join('hr_appointment_details', 'hr_appointments.id', '=', 'hr_appointment_details.hr_appointment_id')
        // ->join('hr_departments', 'hr_departments.id', '=', 'hr_appointment_details.hr_department_id')
        // ->select('hr_departments.*')
        // ->first();

        // $employeeSalary = HrAppointment::where('hr_employee_id', session('hr_employee_id'))
        // ->join('hr_appointment_details', 'hr_appointments.id', '=', 'hr_appointment_details.hr_appointment_id')
        // ->join('hr_salaries', 'hr_salaries.id', '=', 'hr_appointment_details.hr_salary_id')
        // ->select('hr_salaries.*')
        // ->first();


        if($request->ajax()){
            $view =  view('hr.appointment.edit', compact('data','salaries','designations','managers','departments','letterTypes','projects','offices','hrGrades','hrCategories','hrEmployeeTypes','employeeProject'))->render();
            return response()->json($view);
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }

    }


    public function update(AppointmentStore $request, $id){
        //ensure client end is is not changed
        if($id != session('hr_employee_id')){
            return response()->json(['status'=> 'Not OK', 'message' => "Security Breach. No Data Change "]);
        }

    	$input = $request->all();
            if($request->filled('joining_date')){
            $input ['joining_date']= \Carbon\Carbon::parse($request->joining_date)->format('Y-m-d');
            }
            if($request->filled('expiry_date')){
            $input ['expiry_date']= \Carbon\Carbon::parse($request->expiry_date)->format('Y-m-d');
            }
        $input['hr_employee_id']=session('hr_employee_id');
        
        $appointment = HrAppointment::where('hr_employee_id',$id)->first();
       
        $url = $request->url;
        $url = intval(filter_var($url, FILTER_SANITIZE_NUMBER_INT));

        if ($url === session('hr_employee_id')){
            DB::transaction(function () use ($input, $appointment) {  
                //check if appointment exist then update else create
                if ($appointment){

                    HrAppointment::findOrFail($appointment->id)->update($input);
                     
                }else{
        		  HrAppointment::create($input);
                }

                $input['effective_date']=$input['joining_date'];
                $input['hod_id']=$input['hr_manager_id'];

                EmployeeDesignation::updateOrCreate(['hr_employee_id' => session('hr_employee_id')],
                $input);
                EmployeeManager::updateOrCreate(['hr_employee_id' => session('hr_employee_id')],
                $input);


        	}); // end transcation

          return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Update"]);

        }else
        {
            return response()->json(['status'=> 'Not OK', 'message' => "Multiple Tabs Opened, please closed all Tabs and try again"]);
        }

    }

}
