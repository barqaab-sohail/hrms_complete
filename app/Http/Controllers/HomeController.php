<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Charts\Hr\DepartmentChart;
use Illuminate\Support\Facades\Auth;
use App\DataTables\Leave\LeaveBalanceDataTable;
//use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // public function test(LeaveBalanceDataTable $dataTable)
    // {
       
    //     return $dataTable->render('test');
    // }

    public function index()
    {

        // $allRoutes = Route::getRoutes();
        // dd($allRoutes);

        $countBelowForty = ageChart()['countBelowForty'];
        $countBelowFifty = ageChart()['countBelowFifty'];
        $countBelowSixty = ageChart()['countBelowSixty'];
        $countBelowSeventy = ageChart()['countBelowSeventy'];
        $countAboveSeventy = ageChart()['countAboveSeventy'];
 


        $categoryA = categoryChart()['categoryA'];
        $categoryB = categoryChart()['categoryB'];
        $categoryC = categoryChart()['categoryC'];

        $allEmployees = engineerChart()['allEmployees'];
        $pecRegisteredEngineers = engineerChart()['pecRegisteredEngineers'];
        $associatedEngineers = engineerChart()['associatedEngineers'];

        $finance = departmentChart()['finance'];
        $power = departmentChart()['power'];
        $water = departmentChart()['water'];

        
        // $results = projectInvoiceRight(2);
        // // // foreach ($results as $result){
        // // //     echo $result->pr_detail_id.'<br>';
        // // // }
        // dd ($results);

        return view('dashboard.dashboard',compact('countBelowForty','countBelowFifty','countBelowSixty','countBelowSeventy','countAboveSeventy','categoryA','categoryB','categoryC','allEmployees','pecRegisteredEngineers','associatedEngineers','finance','power','water'));
    }

}
