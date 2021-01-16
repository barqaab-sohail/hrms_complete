<?php

namespace App\Http\Controllers\Input;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Input\InputMonthStore;
use App\Models\Input\HrInputMonth;
use DB;

class InputMonthController extends Controller
{
    public function create(){
    	
    	
    	$months = ['January','February', 'March','April', 'May','June','July','August','September','October', 'November', 'December'];
    	$years = ['2021','2022'];

    	$monthYears = HrInputMonth::all();
 
    	return view ('input.inputMonth.create',compact('years','months','monthYears'));
    }

    public function store (InputMonthStore $request){

    	$input = $request->all();
		   	
            DB::transaction(function () use ($input, &$data) {  
                $data = HrInputMonth::create($input);
            }); // end transcation
    	return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved", 'data'=>$data]);

    }

    public function destroy($id){
    	// Comitted due to casecade delete data
    	//HrInputMonth::findOrFail($id)->delete();
    	return response()->json('OK');
   }




}
