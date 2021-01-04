<?php

namespace App\Http\Controllers\Input;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MonthlyInput\HrMonthlyInput;
use App\Models\MonthlyInput\HrMonthlyInputProject;
use App\Http\Requests\MonthlyInput\MonthlyInputStore;
use App\Models\Project\PrDetail;
use DB;

class InputProjectController extends Controller
{
    public function create(){
    	
    	$projects = PrDetail::all();
    	//$months = ['January','Febrary', 'March','April', 'May','June','July','August','September','October', 'November', 'December'];
    	//$years = ['2021'];

    	$monthYears = HrMonthlyInput::where('is_lock',0)->get();
       
    	return view ('input.inputProject.create',compact('monthYears','projects'));
    }

    public function store(MonthlyInputStore $request){ 	
		   	$input = $request->all();
		   	
            DB::transaction(function () use ($input) {  
                HrMonthlyInputProject::create($input);
            }); // end transcation

            
            return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved"]);
    }

    public function show($id){
    	$hrInputProjects = HrMonthlyInputProject::where('hr_monthly_input_id',$id)->with('prDetail')->get();
    	return response()->json($hrInputProjects);

    }
}
