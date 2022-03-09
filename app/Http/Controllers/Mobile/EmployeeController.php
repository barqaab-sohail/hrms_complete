<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDocumentation;

class EmployeeController extends Controller
{
    
    public function index(){
    	
    	$data = HrEmployee::with('employeeDesignation','employeeProject','employeeOffice','employeeAppointment','hrContactMobile')->get();


    	//first sort with respect to Designation
            $designations = employeeDesignationArray();
            $data = $data->sort(function ($a, $b) use ($designations) {
              $pos_a = array_search($a->designation??'', $designations);
              $pos_b = array_search($b->designation??'', $designations);
              return $pos_a - $pos_b;
            });
       
     //   // second sort with respect to Hr Status
            $hrStatuses = array('On Board','Resigned','Terminated','Retired','Long Leave','Manmonth Ended','Death');

            $data = $data->sort(function ($a, $b) use ($hrStatuses) {
              $pos_a = array_search($a->hr_status_id??'', $hrStatuses);
              $pos_b = array_search($b->hr_status_id??'', $hrStatuses);
              return $pos_a - $pos_b;
            });

         
         foreach($data as $employee){
         	$picture = HrDocumentation::where('hr_employee_id',$employee->id)->where('description','Picture')->first();
         	if($picture){
         		$picture = asset('storage/'.$picture->path.$picture->file_name);
         	}

         	$employees[] =  array("id" => $employee->id,
         						"employee_no" => $employee->employee_no,
         						"full_name" => $employee->full_name,
         						"cnic"=>$employee->cnic,
         						"designation"=>$employee->designation,
         						"picture"=>$picture);
         }
    	// check variable size
        // $serializedFoo = serialize($employees);
        // $size = mb_strlen($serializedFoo, '8bit');
        // dd($size);
      
       
        // dd(asset('storage/'.$hremployee->picture->path . $hremployee->picture->file_name));
    	return response()->Json($employees);
    }

}
