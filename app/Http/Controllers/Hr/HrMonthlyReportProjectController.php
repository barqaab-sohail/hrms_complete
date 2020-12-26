<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Hr\HrMonthlyReportProjectStore;
use App\Models\Hr\HrMonthlyReportProject;

use DB;

class HrMonthlyReportProjectController extends Controller
{
    public function store(HrMonthlyReportProjectStore $request){ 	
		   	$input = $request->all();
		   	
            DB::transaction(function () use ($input) {  
                HrMonthlyReportProject::create($input);
            }); // end transcation

            
            return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved"]);
    }


    public function refreshTable(){

    	$hrMonthlyReportProjects = HrMonthlyReportProject::all();
       
        return view('hr.hrMonthlyReport.hrMonthlyProject.list',compact('hrMonthlyReportProjects'));
        
    }

}
