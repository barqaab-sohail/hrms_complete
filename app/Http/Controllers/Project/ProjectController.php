<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrRole;
use App\Models\Project\PrStatus;
use App\Models\Project\PrDetail;
use App\Models\Common\Client;
use App\Models\Common\ContractType;
use App\Http\Requests\Project\PrDetailStore;
use DB;


class ProjectController extends Controller
{
    

	public function index(){
		$projects = PrDetail::whereNotIn('name', array('overhead'))->get();

		return view ('project.detail.list', compact('projects'));

	}

	public function create(){

		session()->put('pr_detail_id', '');
		$projectRoles = PrRole::all();
		$projectStatuses = PrStatus::all();
		$clients = Client::all();
		$contractTypes = ContractType::all();
    	        
    	return view ('project.detail.create', compact('projectRoles','projectStatuses','clients','contractTypes'));

	}

	public function store (PrDetailStore $request){

		$input = $request->all();

		if($request->filled('commencement_date')){
            $input ['commencement_date']= \Carbon\Carbon::parse($request->commencement_date)->format('Y-m-d');
            }
        if($request->filled('contractual_completion_date')){
            $input ['contractual_completion_date']= \Carbon\Carbon::parse($request->contractual_completion_date)->format('Y-m-d');
            }
        if($request->filled('actual_completion_date')){
            $input ['actual_completion_date']= \Carbon\Carbon::parse($request->actual_completion_date)->format('Y-m-d');
            }


		 DB::transaction(function () use ($input) {  
           
           PrDetail::create($input);

    	}); // end transcation


		return response()->json(['status'=> 'OK', 'message' => 'Project Sucessfully Saved']);

	}


	public function edit($id){

		session()->put('pr_detail_id', $id);
		$data = PrDetail::find($id);
		$projectRoles = PrRole::all();
		$projectStatuses = PrStatus::all();
		$clients = Client::all();
		$contractTypes = ContractType::all();
    	        
    	return view ('project.detail.edit', compact('projectRoles','projectStatuses','clients','contractTypes','data'));

	}

	public function update(PrDetailStore $request, $id){

		$input = $request->all();

		if($request->filled('commencement_date')){
            $input ['commencement_date']= \Carbon\Carbon::parse($request->commencement_date)->format('Y-m-d');
            }
        if($request->filled('contractual_completion_date')){
            $input ['contractual_completion_date']= \Carbon\Carbon::parse($request->contractual_completion_date)->format('Y-m-d');
            }
        if($request->filled('actual_completion_date')){
            $input ['actual_completion_date']= \Carbon\Carbon::parse($request->actual_completion_date)->format('Y-m-d');
            }

        DB::transaction(function () use ($input, $id) {  
           
           PrDetail::findOrFail($id)->update($input);

    	}); // end transcation


    	return response()->json(['status'=> 'OK', 'message' => 'Data Sucessfully Updated']);
        
	}


	public function destroy($id){

		PrDetail::findOrFail($id)->delete();

		return back()->with('message', 'Data Sucessfully Updated');

	}





}
