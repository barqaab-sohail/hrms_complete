<?php

namespace App\Http\Controllers\Input;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDesignation;
use App\Models\MonthlyInput\HrMonthlyInputProject;
use App\Models\MonthlyInput\HrMonthlyInput;
use App\Models\MonthlyInput\HrMonthlyInputEmployee;
use App\Http\Requests\MonthlyInput\EmployeeInputStore;

use DB;

class InputController extends Controller
{
    public function create(){

    	$hrInputMonths = HrMonthlyInput::where('is_lock',0)->get();
    	$hrEmployees = HrEmployee::where('hr_status_id',1)->get();
    	$hrDesignations = HrDesignation::all();
    	return view ('input.create',compact('hrInputMonths','hrEmployees','hrDesignations'));
    }


   public function projectList($id, $month){

   	$hrInputProjects = HrMonthlyInputProject::where('hr_monthly_input_id',$month)->where('pr_detail_id',$id)->with('prDetail','hrEmployee','hrDesignation','hrMonthlyInputEmployee')->first();
    	return response()->json($hrInputProjects);


   }

   public function store(EmployeeInputStore $request){

   			$input = $request->all();
            
            DB::transaction(function () use ($input, &$data) {  
                $data = HrMonthlyInputEmployee::create($input);
            }); // end transcation
            //$data = $data->with('hrEmployee')->get();
            $data = HrMonthlyInputEmployee::where('id',$data->id)->with('hrEmployee','hrDesignation')->first();
            return response()->json($data);

   }

   public function destroy($id){
    HrMonthlyInputEmployee::findOrFail($id)->delete();
    return response()->json('OK');
   }
}
