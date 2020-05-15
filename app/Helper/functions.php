<?php
use App\Models\Cv\CvSpecialization;
use App\Models\CV\CvDetail;



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



