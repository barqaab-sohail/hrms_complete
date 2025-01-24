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

        $departmentChart = ["Power - ($power)" => $power, "Water - ($water)" => $water, "Finance - ($finance)" => $finance];
        $employeeSkillChart = ["Other Employees - ($othersEmployees)" => $othersEmployees, "PEC Registered Employees - ($pecRegisteredEngineers)" => $pecRegisteredEngineers, "Associated Engineers - ($associatedEngineers)" => $associatedEngineers];
        $employeeCategoryChart = ["Category A - ($categoryA)" => $categoryA, "Category B - ($categoryB)" => $categoryB, "Category C - ($categoryC)" => $categoryC];
        $employeeAgeChart = ["Below Forty Years - ($countBelowForty)" => $countBelowForty, "Between 40 to 50 Years - ($countBelowFifty)" => $countBelowFifty, "Between 50 to 60 Years - ($countBelowSixty)" => $countBelowSixty, "Between 60 to 70 Years - ($countBelowSeventy)" => $countBelowSeventy, "Above Seventy Years - ($countAboveSeventy)" => $countAboveSeventy];



        return response()->json(['departmentChart' => $departmentChart, 'employeeSkillChart' => $employeeSkillChart, 'employeeCategoryChart' => $employeeCategoryChart, 'employeeAgeChart' => $employeeAgeChart]);
    }
}
