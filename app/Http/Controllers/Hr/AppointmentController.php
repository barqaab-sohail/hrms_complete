<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrAppointment;
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
    	$employees = HrEmployee::all();
    	$departments = HrDepartment::all();
    	$letterTypes = HrLetterType::all();
    	$projects = PrDetail::all();
        $offices = Office::all();
        $hrGrades = HrGrade::all();
        $hrCategories = HrCategory::all();
        $hrEmployeeTypes = HrEmployeeType::all();
    	
        $data = HrAppointment::where('hr_employee_id',session('hr_employee_id'))
          ->first();

        if($request->ajax()){
            $view =  view('hr.appointment.edit', compact('data','salaries','designations','employees','departments','letterTypes','projects','offices','hrGrades','hrCategories','hrEmployeeTypes'))->render();
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
       
            DB::transaction(function () use ($input, $appointment) {  
                //check if appointment exist then update else create
                if ($appointment){

                    HrAppointment::findOrFail($appointment->id)->update($input);
                    $input['effective_date'] = $input ['joining_date'];
                    EmployeeGrade::where('hr_employee_id',session('hr_employee_id'))->first()->update($input);
                    EmployeeProject::where('hr_employee_id',session('hr_employee_id'))->first()->update($input);
                    EmployeeCategory::where('hr_employee_id',session('hr_employee_id'))->first()->update($input);
                    EmployeeDesignation::where('hr_employee_id',session('hr_employee_id'))->first()->update($input);
                    EmployeeDepartment::where('hr_employee_id',session('hr_employee_id'))->first()->update($input);
                    EmployeeManager::where('hr_employee_id',session('hr_employee_id'))->first()->update($input);
                    EmployeeOffice::where('hr_employee_id',session('hr_employee_id'))->first()->update($input);
                    EmployeeSalary::where('hr_employee_id',session('hr_employee_id'))->first()->update($input);
                }else{
        		  HrAppointment::create($input);
                  EmployeeGrade::create($input);
                  EmployeeProject::create($input);
                  EmployeeCategory::create($input);
                  EmployeeDesignation::create($input);
                  EmployeeDepartment::create($input);
                  EmployeeManager::create($input);
                  EmployeeOffice::create($input);
                  EmployeeSalary::create($input);
                  
                }



        	}); // end transcation

          return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Update"]);


    }

}
