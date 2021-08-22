<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Charts\Hr\DepartmentChart;

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
    public function index()
    {



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


       
        return view('dashboard.dashboard',compact('countBelowForty','countBelowFifty','countBelowSixty','countBelowSeventy','countAboveSeventy','categoryA','categoryB','categoryC','allEmployees','pecRegisteredEngineers','associatedEngineers','finance','power','water'));
    }

}
