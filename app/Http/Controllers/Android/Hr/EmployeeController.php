<?php

namespace App\Http\Controllers\Android\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDocumentation;

class EmployeeController extends Controller
{
    public function ageChart(){
    	
    	$countBelowForty= HrEmployee::where('date_of_birth','>=',\Carbon\Carbon::now()->subYears(40))->whereIn('hr_status_id',array(1,5))->count();

	 	$countBelowFifty= HrEmployee::where('date_of_birth','>=',\Carbon\Carbon::now()->subYears(50))->whereIn('hr_status_id',array(1,5))->count() - $countBelowForty;

	 	$countBelowSixty= HrEmployee::where('date_of_birth','>=',\Carbon\Carbon::now()->subYears(60))->whereIn('hr_status_id',array(1,5))->count() - $countBelowForty - $countBelowFifty;

	 	$countBelowSeventy= HrEmployee::where('date_of_birth','>=',\Carbon\Carbon::now()->subYears(70))->whereIn('hr_status_id',array(1,5))->count() - $countBelowForty - $countBelowFifty - $countBelowSixty;

	 	$countAboveSeventy= HrEmployee::whereIn('hr_status_id',array(1,5))->count()-$countBelowForty-$countBelowFifty - $countBelowSixty - $countBelowSeventy;


	 	$data = [
	 		array('label'=>'Below Forty','value'=>$countBelowForty),
	 		array('label'=>'Between 40-50','value'=>$countBelowFifty),
	 		array('label'=>'Between 50-60','value'=>$countBelowSixty),
	 		array('label'=>'Between 60-70','value'=>$countBelowSeventy),
	 		array('label'=>'Aoove Seventy','value'=>$countAboveSeventy)];


    	return response()->json($data);
    	
    }

    public function employees(){

    	// $data = HrEmployee::whereIn('hr_status_id',array(1,5))->get();
    	// return response()->json($data);

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

         $defaultPicture = asset('Massets/images/default.png');
         foreach($data as $employee){
         	$picture = HrDocumentation::where('hr_employee_id',$employee->id)->where('description','Picture')->first();
         	if($picture){
         		$picture = asset('storage/'.$picture->path.$picture->file_name);
         	}else{
         		$picture = $defaultPicture;
         	}

         	$employees[] =  array("id" => $employee->id,
         						"employee_no" => $employee->employee_no,
         						"full_name" => $employee->full_name,
         						"date_of_birth" => \Carbon\Carbon::parse($employee->date_of_birth)->format('M d, Y'),
         						"date_of_joining"=>\Carbon\Carbon::parse($employee->employeeAppointment->joining_date??'')->format('M d, Y'),
         						"cnic"=>$employee->cnic,
         						"designation"=>$employee->designation,
         						"picture"=>$picture,
         						"mobile"=>$employee->hrContactMobile->mobile??'',
         						"status"=>$employee->hr_status_id??'');
         }

         return response()->json ($employees);

    }

    public function documents($hrEmployeeId){

    	$employeeDocuments = HrDocumentation::where('hr_employee_id',$hrEmployeeId)->get();

    	foreach ($employeeDocuments as $document){
    		$documents[] = array("id"=>$document->id,
    							"description"=>$document->description,
    							"extension"=>strtolower($document->extension),
    							"url"=>asset('storage/'.$document->path.$document->file_name)
    							);
    	}

    	return response()->json($documents);

    }
}
