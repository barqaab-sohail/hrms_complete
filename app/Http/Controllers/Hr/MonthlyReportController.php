<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MonthlyReportController extends Controller
{
    
	public function create(Request $request){
    	
            return view('hr.reports.monthly.create');
        }


}
