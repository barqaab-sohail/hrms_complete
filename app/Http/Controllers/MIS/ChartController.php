<?php

namespace App\Http\Controllers\MIS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChartController extends Controller
{
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
        $othersEmployees =  $allEmployees -  $associatedEngineers -  $pecRegisteredEngineers;

        $finance = departmentChart()['finance'];
        $power = departmentChart()['power'];
        $water = departmentChart()['water'];

        $departmentChart = ['Power' => $power, 'Water' => $water, 'Finance' => $finance];
        $employeeSkillChart = ['Other Employees' => $othersEmployees, 'PEC Registered Employees' => $pecRegisteredEngineers, 'Associated Engineers' => $associatedEngineers];
        $employeeCategoryChart = ['Category A' => $categoryA, 'Category B' => $categoryB, 'Category C' => $categoryC];
        $employeeAgeChart = ['Below Forty' => $countBelowForty, 'Below Fifty' => $countBelowFifty, 'Below Sixty' => $countBelowSixty, 'Below Seventy' => $countBelowSeventy, 'Above Seventy' => $countAboveSeventy];



        return response()->json(['departmentChart' => $departmentChart, 'employeeSkillChart' => $employeeSkillChart, 'employeeCategoryChart' => $employeeCategoryChart, 'employeeAgeChart' => $employeeAgeChart]);
    }
}
