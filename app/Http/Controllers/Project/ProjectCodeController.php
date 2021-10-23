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
	    $years =  array();
	    $number=1999;
	    	for ($i=0;$i<22;$i++){
	    		$number = $number+1;
	    		array_push($years, $number);
	    	}

	    return view ('project.code.create', compact('years'));
	}

	public function store (Request $request){

			$code = substr($request->year,-2);
			$count = 1;
			$code = $code.'0'; //200
			$projectCode = $code.$count;
		
		while(PrDetail::where('project_no',$code.$count)->count()>0){ 
			$count++;
			if($count>9){	
				$code=mb_substr($code, 0, 2); 
			}
			$projectCode = $code.$count;
		}
		

		return response()->json(['status'=> 'OK', 'message' => "Project Code Generated Successfully", 'code'=>$projectCode]);
	}
}
