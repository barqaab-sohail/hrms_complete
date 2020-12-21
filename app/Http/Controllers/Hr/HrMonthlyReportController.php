<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HrMonthlyReportController extends Controller
{
    public function create(){

    	return view ('hr.HrMonthlyReport.create');
    }
}
