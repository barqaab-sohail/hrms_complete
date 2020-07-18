<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrDivision;
use App\Models\Project\PrCategory;
use App\Models\Project\PrWorkType;
use App\Models\Project\PrDetail;


class ProjectCodeController extends Controller
{
    
    public function create(){
	    $categories = PrCategory::all();
	    $divisions = PrDivision::all();
	    $workTypes = PrWorkType::all();

	    return view ('project.code.create', compact('divisions', 'categories', 'workTypes'));
	}

	public function store (Request $request){

		$code = $request->pr_division_id . $request->pr_category_id . $request->pr_work_type_id;
		$count = 1;
		$projectCode = $code.$count;

		while(PrDetail::where('project_no',$code.$count)->count()>0){
			$count++;
			$projectCode = $code.$count;
		}

	

		return response()->json(['status'=> 'OK', 'message' => "Project Code Generated Sucessfully", 'code'=>$projectCode]);
	}
}
