<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Project\PrDetail;
use App\Models\Project\PrRight;
use App\User;

class ProjectRightController extends Controller
{
    
   	public function show($id){

			
			$data = PrRight::where('hr_employee_id',3)->get();

	  		return view('project.rights.rightsTable',compact('data'));

    }



    public function create(){
    	
    	$rights = rights();
    	$employees = HrEmployee::all();
    	$projects = PrDetail::all();
		return view ('project.rights.create', compact('employees','projects','rights'));
	}


	public function store(Request $request){



	}

	



}
