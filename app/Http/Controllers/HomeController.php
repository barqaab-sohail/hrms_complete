<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\EmployeeManager;
use App\Charts\Hr\DepartmentChart;
use Illuminate\Support\Facades\Auth;
use App\DataTables\Leave\LeaveBalanceDataTable;
use Illuminate\Support\Facades\RateLimiter;
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

        return view('dashboard.dashboard', compact('countBelowForty', 'countBelowFifty', 'countBelowSixty', 'countBelowSeventy', 'countAboveSeventy', 'categoryA', 'categoryB', 'categoryC', 'allEmployees', 'pecRegisteredEngineers', 'associatedEngineers', 'finance', 'power', 'water'));
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
            $data = HrEmployee::with('picture', 'employeeAppointment')->where('cnic', $id)->first();
            if ($data) {
                return response()->json(['data' => $data]);
            } else {
                return response()->json(['error' => 'Not Data Found']);
            }
        } else {
            $data = HrEmployee::with('picture', 'employeeAppointment')->where('employee_no', $id)->first();
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
            $perMinute = 5,
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



    public function test()
    {

        $manager = HrEmployee::find(280);
        echo employeeFullName($manager->hod->hr_manager_id) ?? '' . '<br>';

        // foreach ($manager->employeeManager as $employee) {
        //     echo employeeFullName($employee->hr_employee_id)  . '<br>';
        // }
    }
}
