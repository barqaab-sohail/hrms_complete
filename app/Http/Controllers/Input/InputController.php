<?php

namespace App\Http\Controllers\Input;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDesignation;
use App\Models\Input\HrInputProject;
use App\Models\Input\HrInputMonth;
use App\Models\Input\HrInput;
use App\Http\Requests\Input\HrInputStore;

use DB;

class InputController extends Controller
{
    public function create(){

    	$hrInputMonths = HrInputMonth::where('is_lock',0)->get();
    	$hrEmployees = HrEmployee::where('hr_status_id',1)->get();
    	$hrDesignations = HrDesignation::all();
    	return view ('input.create',compact('hrInputMonths','hrEmployees','hrDesignations'));
    }


   public function projectList($id, $month){

   	$hrInputProjects = HrInputProject::where('hr_input_month_id',$month)->where('pr_detail_id',$id)->with('prDetail','hrEmployee','hrDesignation','hrMonthlyInputEmployee')->first();
    	return response()->json($hrInputProjects);


   }

   public function store(HrInputStore $request){

   			$input = $request->all();
            
            DB::transaction(function () use ($input, &$data) {  
                $data = HrInput::create($input);
            }); // end transcation
            //$data = $data->with('hrEmployee')->get();
            $data = HrInput::where('id',$data->id)->with('hrEmployee','hrDesignation')->first();
            return response()->json($data);

   }

   public function destroy($id){
    HrInput::findOrFail($id)->delete();
    return response()->json('OK');
   }
}
