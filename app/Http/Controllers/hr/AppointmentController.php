<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrAppointment;
use App\Models\Hr\HrSalary;

class AppointmentController extends Controller
{
    public function edit(Request $request, $id){

    	$salaries = HrSalary::all();

	    
	   if($request->ajax()){
	    return view('hr.appointment.edit', compact('salaries'));
	  }

    }





}
