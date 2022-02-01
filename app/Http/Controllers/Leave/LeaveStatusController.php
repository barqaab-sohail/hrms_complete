<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave\LeStatusType;

class LeaveStatusController extends Controller
{
    

    public function index(){

    	$leStatusTypes = LeStatusType::all();

    	return response()->json($leStatusTypes);

    }
}
