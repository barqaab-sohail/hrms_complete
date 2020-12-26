<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrMonthlyReport;
use App\Models\Project\PrDetail;
use App\Http\Requests\Hr\MonthlyReportStore;
use DB;

class HrMonthlyReportController extends Controller
{
    public function create(){


    	$hrMonthlyReports = HrMonthlyReport::all();
    	$projects = PrDetail::all();

    	$months = ['January','Febrary', 'March','April', 'May','June','July','August','September','October', 'November', 'December'];


    	return view ('hr.HrMonthlyReport.create',compact('months','hrMonthlyReports','projects'));
    }


    public function store(MonthlyReportStore $request){ 	
		   	$input = $request->all();
		   	if($request->filled('date')){
            $input ['date']= \Carbon\Carbon::parse($request->date)->format('Y-m');
            }

            DB::transaction(function () use ($input) {  
                HrMonthlyReport::create($input);
            }); // end transcation

            
            return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved"]);
    }
}
