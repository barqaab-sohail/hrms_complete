<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrCostType;
use App\Models\Common\Partner;


class ProjectConsultancyCostController extends Controller
{
    
	public function create(){

		$prCostTypes = PrCostType::all();
		$partners = Partner::all();


	return view ('project.consultancyCost.create',compact('prCostTypes','partners'));

	}


}
