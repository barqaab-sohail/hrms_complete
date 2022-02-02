<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use DataTables;

class LeaveBalanceController extends Controller
{
    public function index(){

        // 	$result = collect(HrEmployee::join('employee_categories','employee_categories.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','employee_categories.hr_category_id','employee_categories.effective_date as cat')->whereIn('hr_status_id',array(1,5))->orderBy('cat','desc')->get());
        //     	$resultUnique = ($result->unique('id'));
        //     	$resultUnique->values()->all();
        // $employees = $resultUnique->whereIn('hr_category_id',array(1,2));

        //  //first sort with respect to Designation
        //     $designations = employeeDesignationArray();
        //     $employees = $employees->sort(function ($a, $b) use ($designations) {
        //       $pos_a = array_search($a->employeeDesignation->last()->name??'', $designations);
        //       $pos_b = array_search($b->employeeDesignation->last()->name??'', $designations);
        //       return $pos_a - $pos_b;
        //     });
        $employees = HrEmployee::with('hod')->get();
        return view ('leave.leaveBalance', compact('employees'));
    }

    public function create(Request $request) {

        if ($request->ajax()) {     

            $data = HrEmployee::with('hod')->get();
          
            return DataTables::of($data)
              ->addIndexColumn()
              ->addColumn('fullName', function($row){                
                
                     return employeeFullName($row->id);     
              })
              ->addColumn('casual_leave', function($row){                
                
                     return 'Casual';     
              })
              ->addColumn('annual_leave', function($row){                
                
                     return 'Annual';     
              })
              ->rawColumns(['fullName','casual_leave','annual_leave'])
              ->make(true);
        }

        $employees = HrEmployee::with('hod')->get();
        return view ('leave.leaveBalance', compact('employees'));
    }


}
