<?php

namespace App\Http\Controllers\Android\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function ageChart(){

    	return response()->json(ageChart());
    }
}
