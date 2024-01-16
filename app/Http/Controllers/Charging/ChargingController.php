<?php

namespace App\Http\Controllers\Charging;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;



class ChargingController extends Controller
{
    public function create(){
    	$employees = HrEmployee::all();

	   	return view('charging.create',compact('employees'));
    
    }
}
