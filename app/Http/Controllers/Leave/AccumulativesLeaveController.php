<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave\LeAccumulative;
use App\Models\Hr\HrEmployee;
use App\Models\Leave\LeTypes;

class AccumulativesLeaveController extends Controller
{
    public function index() {
        
        $leAccumulatives = LeAccumulative::all();
        	$result = collect(HrEmployee::join('employee_categories','employee_categories.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','employee_categories.hr_category_id','employee_categories.effective_date as cat')->whereIn('hr_status_id',array(1,5))->orderBy('cat','desc')->get());
            	$resultUnique = ($result->unique('id'));
            	$resultUnique->values()->all();
        $hrEmployees = $resultUnique->where('hr_category_id',1);
        $leTypes = LeType::where('name','Annual Leave')->first();
        $view =  view('leave.accumulative_leave.create', compact('leAccumulatives','hrEmployees','leTypes'))->render();
        return response()->json($view);
    }
}
