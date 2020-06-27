<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;

class HrReportsController extends Controller
{
    
    public function list(){

    	return view ('hr.reports.list');
    }


    public function cnicExpiryList(){


    	$date = \Carbon\Carbon::now()->format('Y-m-d');

    	
    	$employees = HrEmployee::where('cnic_expiry','<',$date)->where('hr_status_id',1)->get();


    	return view ('hr.reports.cnicExpiryList', compact('employees'));

    }

    public function missingDocumentList(){

    	$employees = HrEmployee::where('hr_status_id',1)->with('appointmentLetter','cnicFront','hrForm','engineeringDegree','educationalDocuments')->get();

    	return view ('hr.reports.missingDocumentList', compact('employees'));

    }
}
