<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrRole;

class ProjectPartnerController extends Controller
{
    
	public function create(){

		$prRoles = PrRole::all();


		return view ('project.partner.create',compact('prRoles'));


	}


}
