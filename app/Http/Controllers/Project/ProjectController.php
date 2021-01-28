<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrRole;
use App\Models\Project\PrStatus;
use App\Models\Project\PrDetail;
use App\Models\Project\PrDivision;
use App\Models\Common\Client;
use App\Models\Common\ContractType;
use App\Http\Requests\Project\PrDetailStore;
use DB;

use App\Imports\ProjectsImport;


class ProjectController extends Controller
{
    

	public function index(){
    //$user = User::permission()
		$projects =collect();
    $waterProjects = PrDetail:: where('pr_division_id',1)->whereNotIn('name', array('overhead'))->get();
    $powerProjects = PrDetail::where('pr_division_id',2)->get();
		return view ('project.detail.list', compact('projects','waterProjects','powerProjects'));

	}

	public function create(){

		session()->put('pr_detail_id', '');
		$projectRoles = PrRole::all();
		$projectStatuses = PrStatus::all();
		$clients = Client::all();
		$contractTypes = ContractType::all();
    $divisions = PrDivision::all();
    	        
    	return view ('project.detail.create', compact('projectRoles','projectStatuses','clients','contractTypes','divisions'));

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


	public function edit(Request $request, $id){

		session()->put('pr_detail_id', $id);
		$data = PrDetail::find($id);
		$projectRoles = PrRole::all();
		$projectStatuses = PrStatus::all();
		$clients = Client::all();
		$contractTypes = ContractType::all();
        $divisions = PrDivision::all();

    if($request->ajax()){      
            return view ('project.detail.ajax', compact('projectRoles','projectStatuses','clients','contractTypes','divisions','data'));    
        }else{
            return view ('project.detail.edit', compact('projectRoles','projectStatuses','clients','contractTypes','divisions','data'));       
        }
	}

	public function update(PrDetailStore $request, $id){
        if($id != session('pr_detail_id')){
            return response()->json(['status'=> 'Not OK', 'message' => "Security Breach. No Data Change "]);
        }

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

        
    	return response()->json(['status'=> 'OK', 'message' => "$id Data Sucessfully Updated"]);
        
	}


	public function destroy($id){

		PrDetail::findOrFail($id)->delete();

		return back()->with('message', 'Data Sucessfully Updated');

	}


	function import(Request $request)
    {
     	
     $this->validate($request, [
      'select_file'  => 'required|mimes:xls,xlsx'
     ]);

     	$path = $request->file('select_file')->getRealPath();
        

     	Excel::import(new ProjectsImport, $path);
         
    return back()->with('success', 'Excel Data Imported successfully.');
    }




}
