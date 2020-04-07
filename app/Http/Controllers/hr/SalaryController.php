<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrSalary;
use DB;

class SalaryController extends Controller
{
     public function store (Request $request){

    

            $testSalary='';
    	DB::transaction(function () use ($request, &$testSalary) {  

    		$testSalary = HrSalary::create(['total_salary'=>$request->total_salary]);

    	}); // end transcation

        //return response()->json(['url'=>url('/dashboard')]);
        
    	return response()->json(['status'=> 'OK', 'message' => 'Data Sucessfully Updated']);
    }
}
