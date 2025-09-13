<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\EmployeeManager;
use App\Charts\Hr\DepartmentChart;
use Illuminate\Support\Facades\Auth;
use App\DataTables\Leave\LeaveBalanceDataTable;
use Illuminate\Support\Facades\RateLimiter;
use DB;
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
        $educations =  educationChart();
        $ageChart = ageChart();
        $countBelowForty = $ageChart['countBelowForty'];
        $countBelowFifty = $ageChart['countBelowFifty'];
        $countBelowSixty = $ageChart['countBelowSixty'];
        $countBelowSeventy = $ageChart['countBelowSeventy'];
        $countAboveSeventy = $ageChart['countAboveSeventy'];


        $categoryChart = categoryChart();
        $categoryA = $categoryChart['categoryA'];
        $categoryB = $categoryChart['categoryB'];
        $categoryC = $categoryChart['categoryC'];

        $engineerChart = engineerChart();
        $allEmployees = $engineerChart['allEmployees'];
        $pecRegisteredEngineers = $engineerChart['pecRegisteredEngineers'];
        $associatedEngineers = $engineerChart['associatedEngineers'];

        $depatmentChart = departmentChart();
        $finance = $depatmentChart['finance'];
        $power = $depatmentChart['power'];
        $water = $depatmentChart['water'];


        // $results = projectInvoiceRight(2);
        // // // foreach ($results as $result){
        // // //     echo $result->pr_detail_id.'<br>';
        // // // }
        // dd ($results);

        return view('dashboard.dashboard', compact('countBelowForty', 'countBelowFifty', 'countBelowSixty', 'countBelowSeventy', 'countAboveSeventy', 'categoryA', 'categoryB', 'categoryC', 'allEmployees', 'pecRegisteredEngineers', 'associatedEngineers', 'finance', 'power', 'water', 'educations'));
    }


    public function employee()
    {
        return view('hr.verification.index');
    }

    public function result($id)
    {
        // Following function restrict maximum 5 request in 1 minute
        $executed = RateLimiter::attempt(
            'send-message:',
            $perMinute = 5,
            function () {
                // Send message...
            }
        );

        if (!$executed) {
            return response()->json(['error' => 'Too many Request sent!, Please retry after some time']);
        }


        if (strlen($id) > 7) {
            $data = HrEmployee::with('picture', 'employeeAppointment', 'employeeCurrentDesignation')->where('cnic', $id)->first();
            if ($data) {
                return response()->json(['data' => $data]);
            } else {
                return response()->json(['error' => 'Not Data Found']);
            }
        } else {
            $data = HrEmployee::with('picture', 'employeeAppointment', 'employeeCurrentDesignation')->where('employee_no', $id)->first();
            if ($data) {
                return response()->json(['data' => $data]);
            } else {
                return response()->json(['error' => 'Not Data Found']);
            }
        }
    }

    public function employeeId($id)
    {

        // Following function restrict maximum 5 request in 1 minute
        $executed = RateLimiter::attempt(
            'send-message:',
            $perMinute = 10,
            function () {
                // Send message...
            }
        );

        if (!$executed) {
            return 'Too many Request sent!, Please retry after some time';
        }




        $data = HrEmployee::with('picture')->where('employee_no', $id)->first();
        if ($data) {
            //return response()->json($data);
            return view('hr.verification.show', compact('data'));
        } else {
            return view('hr.verification.show', compact('data'));
        }
    }
}
