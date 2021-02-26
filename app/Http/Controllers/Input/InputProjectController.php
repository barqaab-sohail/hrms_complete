<?php

namespace App\Http\Controllers\Input;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Input\HrInputMonth;
use App\Models\Input\HrInputProject;
use App\Http\Requests\Input\HrInputProjectStore;
use App\Models\Project\PrDetail;
use DB;

class InputProjectController extends Controller
{
    public function create(){
    	
    	$projects = PrDetail::all();
    	$monthYears = HrInputMonth::where('is_lock',0)->get();
    	return view ('input.inputProject.create',compact('monthYears','projects'));
    }

    public function store(HrInputProjectStore $request){ 	
		   	$input = $request->all();
		   	
            DB::transaction(function () use ($input) {  
                HrInputProject::create($input);
            }); // end transcation

            
            return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);
    }

    public function show($id){
    	$hrInputProjects = HrInputProject::where('hr_input_month_id',$id)->with('prDetail')->get();
    	return response()->json($hrInputProjects);

    }
}
