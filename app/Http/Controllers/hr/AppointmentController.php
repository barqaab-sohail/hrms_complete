<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrAppointment;
use App\Models\Hr\HrAppointmentDetail;
use App\Models\Hr\HrSalary;
use App\Models\Hr\HrSalaryDetail;
use App\Models\Hr\HrDesignation;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDepartment;
use App\Models\Hr\HrLetterType;
use App\Models\Hr\HrCommonModel;
use App\Models\Project\PrDetail;
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
    	$data = HrAppointment::where('hr_employee_id',$id)->first();
        
        $employeeDesignation = HrAppointment::where('hr_employee_id', session('hr_employee_id'))
            ->join('hr_appointment_details', 'hr_appointments.id', '=', 'hr_appointment_details.hr_appointment_id')
            ->join('hr_designations', 'hr_designations.id', '=', 'hr_appointment_details.hr_designation_id')
            ->select('hr_designations.*')
            ->first();

        $employeeHod = HrAppointment::where('hr_employee_id', session('hr_employee_id'))
        ->join('hr_appointment_details', 'hr_appointments.id', '=', 'hr_appointment_details.hr_appointment_id')
        ->join('hr_employees', 'hr_employees.id', '=', 'hr_appointment_details.hr_manager_id')
        ->select('hr_employees.*')
        ->first();

         $employeeDepartment = HrAppointment::where('hr_employee_id', session('hr_employee_id'))
        ->join('hr_appointment_details', 'hr_appointments.id', '=', 'hr_appointment_details.hr_appointment_id')
        ->join('hr_departments', 'hr_departments.id', '=', 'hr_appointment_details.hr_department_id')
        ->select('hr_departments.*')
        ->first();

        $employeeSalary = HrAppointment::where('hr_employee_id', session('hr_employee_id'))
        ->join('hr_appointment_details', 'hr_appointments.id', '=', 'hr_appointment_details.hr_appointment_id')
        ->join('hr_salaries', 'hr_salaries.id', '=', 'hr_appointment_details.hr_salary_id')
        ->select('hr_salaries.*')
        ->first();


        if($request->ajax()){
            return view('hr.appointment.edit', compact('data','salaries','designations','employees','departments','letterTypes','projects','employeeDesignation','employeeHod','employeeDepartment','employeeSalary'));
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }

    }


    public function update(Request $request, $id){
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

            if ($appointment){

                $appointmentDetail = HrAppointmentDetail::where('hr_appointment_id', $appointment->id)->first();
                HrAppointment::findOrFail($appointment->id)->update($input);
                HrAppointmentDetail::findOrFail($appointmentDetail->id)->update($input);    

            }else{
    		$appointment = HrAppointment::create($input);
            $input['hr_appointment_id'] = $appointment->id;
            HrAppointmentDetail::create($input);
            }


    	}); // end transcation


      return response()->json(['status'=> 'OK', 'message' => 'Data Sucessfully Updated']);

    }





}
