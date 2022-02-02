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
      
        if($request->ajax()){

            $data = HrEmployee::where('hr_status_id',1)->get();   
           
            return DataTables::of($data)
      
            ->addColumn('full_name', function($data){
                $full_name = $data->first_name . ' '. $data->last_name;

                return $full_name;
            })
            ->addColumn('casual_leave',function($data){
                return casualLeave($data->id);
            })
            ->addColumn('annual_leave',function($data){

                 return 'annual_leave';
            })
           
            ->rawColumns(['full_name','casual_leave','annual_leave'])
            ->make(true);
        }

       $employees = HrEmployee::with('hod')->get();
        return view ('leave.leaveBalance', compact('employees'));
       
    }

}
