<?php

use App\Models\Cv\CvSpecialization;
use Illuminate\Support\Facades\Auth;
use App\Models\CV\CvDetail;
use App\Models\Project\PrRight;
use App\Models\Common\Office;


function rightsName($id)
{
	if ($id == 1) {
		return 'No Access';
	} else if ($id == 2) {
		return 'View Record';
	} else if ($id == 3) {
		return 'Edit Record';
	} else if ($id == 4) {
		return 'Delete Record';
	} else {
		return '';
	}
}




function projectPaymentRight($project)
{
	if (Auth::user()->hasRole('Super Admin')) {
		return true;
	}

	$projectPaymentRight = PrRight::where('hr_employee_id', Auth::user()->hrEmployee->id)->where('pr_detail_id', $project)->first();
	if ($projectPaymentRight) {
		if ($projectPaymentRight->invoice == 1) {
			return false;
		} else {
			return $projectPaymentRight->payment;
		}
	} else {
		return false;
	}
}

function projectProgressRight($project)
{
	if (Auth::user()->hasRole('Super Admin')) {
		return true;
	}

	$projectProgressRight = PrRight::where('hr_employee_id', Auth::user()->hrEmployee->id)->where('pr_detail_id', $project)->first();

	if ($projectProgressRight) {
		if ($projectProgressRight->progress == 1) {
			return false;
		} else {
			return $projectProgressRight->progress;
		}
	} else {
		return false;
	}
}

function addComma($id)
{

	if ($id) {

		return number_format($id);
	} else {
		return '';
	}
}

function removeComma($id)
{
	$value = intval(str_replace(',', '', $id));
	return $value;
}


function officeName($officeId)
{
	if ($officeId) {

		$office = Office::find($officeId);

		return $office->name;
	} else {
		return '';
	}
}



function cvSpecilizationName($id)
{

	$cvSpecialization = CvSpecialization::find($id);

	return $cvSpecialization->name;
}

function fullName($id)
{

	$cvDetail = CvDetail::find($id);

	return $cvDetail->full_name;
}


function age($dob)
{
	//return $dob->diffInYears(\Carbon::now());
	$years = \Carbon\Carbon::parse($dob)->age;
	return $years;
}



function countPdfPages($path)
{

	$pdftext = json_decode(file_get_contents($path));

	$num = preg_match_all("/\/Page\W/", $pdftext, $dummy);

	return $num;
}
