<?php

namespace App\Http\Controllers\Project;
use Illuminate\Support\Facades\Auth;
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
    $waterProjects = PrDetail::with('client','prRole')->where('pr_division_id',1)->whereNotIn('name', array('overhead'))->get();
    
    //$powerProjects = PrDetail::with('client','prRole')->where('pr_division_id',2)->get();
    $prDetailIds = projectIds(Auth::user()->hrEmployee->id);
    $powerProjects = PrDetail::wherein('id',$prDetailIds)->get();
	
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


		return response()->json(['status'=> 'OK', 'message' => 'Project Successfully Saved']);

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
            // else{
            //     $input ['actual_completion_date']= '';
            // }
            

        DB::transaction(function () use ($input, $id) {  
           
           PrDetail::findOrFail($id)->update($input);

    	}); // end transcation

        
    	return response()->json(['status'=> 'OK', 'message' => "$id Data Successfully Updated"]);
        
	}


	public function destroy($id){

		
        PrDetail::findOrFail($id)->delete();
		return back()->with('message', 'Data Successfully Updated');

	}


	public function import(Request $request)
    {
     	
     $this->validate($request, [
      'select_file'  => 'required|mimes:xls,xlsx'
     ]);

     	$path = $request->file('select_file')->getRealPath();
        

     	Excel::import(new ProjectsImport, $path);
         
    return back()->with('success', 'Excel Data Imported successfully.');
    }

    public function search(){

        return view ('project.search.search');
    }

    public function result(Request $request){
    
        
        if($request->filled('reference_no')){
        $query = $request->input('reference_no');
            
            $result = DB::table('pr_documents')
            ->where('reference_no', 'LIKE', "%{$query}%")
            ->get();
            return view('project.search.searchResult',compact('result'));

        }else if ($request->filled('description')){
           $query = $request->input('description'); 
            
            $result = DB::table('pr_documents')
            ->where('description', 'LIKE', "%{$query}%")
            ->get();
            return view('project.search.searchResult',compact('result'));

       }else if ($request->filled('document_date')){
        $query = \Carbon\Carbon::parse($request->document_date)->format('Y-m-d');

            $result = DB::table('pr_documents')
            ->where('document_date', 'LIKE', "%{$query}%")
            ->get();
            return view('project.search.searchResult',compact('result'));

       } else{

            $result = DB::table('pr_documents')
            ->get();
            return view('project.search.searchResult',compact('result'));

       }

       

    }




}
