<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
class EmployeeController extends Controller
{
    
    public function index(){
    	
    	$employees = HrEmployee::select('first_name','last_name','employee_no')->get();
    	// check variable size
        // $serializedFoo = serialize($employees);
        // $size = mb_strlen($serializedFoo, '8bit');
        // dd($size);

    	return response()->Json($employees);
    }

}
