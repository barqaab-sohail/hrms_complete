<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrSalary;
use App\Http\Requests\Hr\SalaryStore;
use DB;

class SalaryController extends Controller
{
    
    public function store (SalaryStore $request){

       $totalSalary = (int)str_replace(',', '', $request->total_salary);

        $salary = HrSalary::where('total_salary', $totalSalary)->first();
      
        if($salary == null){
            
            DB::transaction(function () use ($totalSalary) {  

                HrSalary::create(['total_salary'=>$totalSalary]);

            }); // end transcation   

            $salaries = DB::table("hr_salaries")
                ->pluck("total_salary","id");
        
            return response()->json(['salaries'=> $salaries, 'message'=>"$totalSalary Salary Successfully Entered"]);
        }else{

            return response()->json(['salaries'=> '', 'message'=>"$totalSalary Salary is already entered"]);
           
        }
      
    	
    }
}
