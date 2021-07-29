<?php
use App\Models\Cv\CvSpecialization;
use App\Models\CV\CvDetail;
use App\Models\Hr\HrEmployee;
use App\Models\Office\Office;

function employeeFullName($id){
	$hremployee = HrEmployee::find($id);
		if($hremployee){
		return $hremployee->first_name. ' '.$hremployee->last_name.' - '.$hremployee->employeeDesignation->last()->name??'';
		}
}


function officeName($id){
	$office = Office::find($id);
	return $office->name;
}



function cvSpecilizationName($id){

    $cvSpecialization = CvSpecialization::find($id);

    return $cvSpecialization->name;
    
   
}

function fullName($id){

	$cvDetail = CvDetail::find($id);

	return $cvDetail->full_name;
}


function age($dob) {
    //return $dob->diffInYears(\Carbon::now());
	$years = \Carbon\Carbon::parse($dob)->age;
    return $years;
}

function getDivision($id){

	if($id==11){

		return "Water";
	}else if ($id==12){
			return "Power";
	}else if ($id==13){
			return "Head Office";
	}else if ($id==14){
			return "Finance";
	}else{
		return "No Code";
	}

}



