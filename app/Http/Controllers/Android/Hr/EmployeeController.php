<?php

namespace App\Http\Controllers\Android\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;

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
}
