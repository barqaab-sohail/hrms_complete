<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrSalary;
use DB;

class SalaryController extends Controller
{
     public function store (Request $request){

        $salary = HrSalary::where('total_salary', $request->total_salary)->first();
      
        if($salary == null){
            
             DB::transaction(function () use ($request) {  

                 HrSalary::create(['total_salary'=>$request->total_salary]);

            }); // end transcation   

            $message = 'Salary Successfully Entered';
        }else{
             $message = "$request->total_salary Salary is already entered";
        }
       

        //return response()->json(['url'=>url('/dashboard')]);

         $salaries = DB::table("hr_salaries")
                ->pluck("total_salary","id");
        
    	return response()->json(['salaries'=> $salaries, 'message'=>$message]);
    }
}
