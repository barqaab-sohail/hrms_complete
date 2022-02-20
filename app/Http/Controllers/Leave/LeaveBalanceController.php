<?php

namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Leave\Leave;
use DataTables;
use App\DataTables\Leave\LeaveBalanceDataTable;

class LeaveBalanceController extends Controller
{
    public function index(LeaveBalanceDataTable $dataTable){
        
        return $dataTable->render('leave.dataTable');
       
    }

}
