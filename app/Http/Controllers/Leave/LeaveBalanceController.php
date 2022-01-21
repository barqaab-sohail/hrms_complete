<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;

class LeaveBalanceController extends Controller
{
    public function index(){

        	$result = collect(HrEmployee::join('employee_categories','employee_categories.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','employee_categories.hr_category_id','employee_categories.effective_date as cat')->whereIn('hr_status_id',array(1,5))->orderBy('cat','desc')->get());
            	$resultUnique = ($result->unique('id'));
            	$resultUnique->values()->all();
        $employees = $resultUnique->whereIn('hr_category_id',array(1,2));

         //first sort with respect to Designation
            $designations = employeeDesignationArray();
            $employees = $employees->sort(function ($a, $b) use ($designations) {
              $pos_a = array_search($a->employeeDesignation->last()->name??'', $designations);
              $pos_b = array_search($b->employeeDesignation->last()->name??'', $designations);
              return $pos_a - $pos_b;
            });

        return view ('leave.leaveBalance', compact('employees'));
    }
}
