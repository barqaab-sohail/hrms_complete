<?php

namespace App\Http\Controllers\Input;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDesignation;
use App\Models\MonthlyInput\HrMonthlyInputProject;
use App\Models\MonthlyInput\HrMonthlyInputEmployee;
use DB;

class InputController extends Controller
{
    public function create(){

    	$hrInputMonths = HrMonthlyInputProject::where('lock_user',0)->get();
    	$hrEmployees = HrEmployee::where('hr_status_id',1)->get();
    	$hrDesignations = HrDesignation::all();
    	return view ('input.create',compact('hrInputMonths','hrEmployees','hrDesignations'));
    }


   public function projectList($id, $month){

   	$hrInputProjects = HrMonthlyInputProject::where('hr_monthly_input_id',$month)->where('pr_detail_id',$id)->with('prDetail')->first();
    	return response()->json($hrInputProjects);


   }

   public function store(Requst $request){

   			$input = $request->all();
            $input['hr_monthly_input_project_id']=1;
            
            DB::transaction(function () use ($input, &$data) {  
                $data = HrMonthlyInputEmployee::create($input);
            }); // end transcation

            return response()->json($data);

   }
}
