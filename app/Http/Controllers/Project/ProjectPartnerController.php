<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrRole;
use App\Models\Common\Partner;
use App\Models\Project\PrPartner;
use App\Models\Project\PrPartnerContact;
use DB;

class ProjectPartnerController extends Controller
{
    
	public function create(){

		$prRoles = PrRole::all();
		$partners = Partner::all();


		return view ('project.partner.create',compact('prRoles','partners'));


	}

	public function store (Request $request){

		$input = $request->all();
		$input['pr_detail_id']= session('pr_detail_id');

		DB::transaction(function () use ($request, $input) {   

			$prPartner=PrPartner::create($input);

			$input['pr_partner_id']= $prPartner->id;

			PrPartnerContact::create($input);

			


		});  //end transaction

		return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);


	}





}
