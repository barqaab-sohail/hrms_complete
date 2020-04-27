<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrSalary;
use DB;

class SalaryController extends Controller
{
    public function store (Request $request){

       // $totalSalary = (int)str_replace(',', '', $request->total_salary);

        $salary = HrSalary::where('total_salary', $request->total_salary)->first();
      
        if($salary == null){
            
             DB::transaction(function () use ($request) {  

                 HrSalary::create(['total_salary'=>$request->total_salary]);

            }); // end transcation   

            $salaries = DB::table("hr_salaries")
                ->pluck("total_salary","id");
        
            return response()->json(['salaries'=> $salaries, 'message'=>"Salary Successfully Entered"]);
        }else{

            return response()->json(['salaries'=> '', 'message'=>"$totalSalary Salary is already entered"]);
           
        }

            
    	
    }
}
