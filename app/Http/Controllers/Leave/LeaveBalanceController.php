<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Leave\Leave;
use DataTables;

class LeaveBalanceController extends Controller
{
    public function index(Request $request){
        
        // $employee=HrEmployee::find(3);
        // dd($employee->employeeCategory->last()->name);
        // $data = HrEmployee::whereIn('employee_no',leaveEmployees())->get();
        // dd($data);
        if($request->ajax()){

            $data = HrEmployee::with('leAccumulative')->where('hr_status_id',1)->whereIn('employee_no',leaveEmployees())->get();   
           
            return DataTables::of($data)
      
            ->addColumn('full_name', function($data){
                $full_name = $data->first_name . ' '. $data->last_name;

                return $full_name;
            })
            ->addColumn('casual_leave',function($data){
                return casualLeave($data->id);
            })
            ->addColumn('annual_leave',function($data){

                 return annualLeave($data->id);
            })

            ->addColumn('accumulative_annual_leave',function($data){

                 return $data->leAccumulative->accumulative_total??'N/A';
            })
           
            ->rawColumns(['full_name','casual_leave','annual_leave','accumulative_annual_leave'])
            ->make(true);
        }

       $employees = HrEmployee::with('hod')->get();
        return view ('leave.leaveBalance', compact('employees'));
       
    }

}
