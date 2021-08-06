<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $countAboveSixty= HrEmployee::where('date_of_birth','<',\Carbon\Carbon::now()->subYears(60))->where('hr_status_id',1)->count();
        $countBelowSixty = HrEmployee::where('hr_status_id',1)
                        ->count()-$countAboveSixty;
        $categoryA = HrEmployee::join('employee_appointments','employee_appointments.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','employee_appointments.hr_category_id')->where('hr_category_id',1)->where('hr_status_id',1)->count();
        $categoryB = HrEmployee::join('employee_appointments','employee_appointments.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','employee_appointments.hr_category_id')->where('hr_category_id',2)->where('hr_status_id',1)->count();
        $categoryC = HrEmployee::join('employee_appointments','employee_appointments.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','employee_appointments.hr_category_id')->where('hr_category_id',3)->where('hr_status_id',1)->count();

        $pecRegisteredEngineers = HrEmployee::join('hr_memberships','hr_memberships.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','hr_memberships.membership_no')->where('hr_status_id',1)->count();
        $associatedEngineers = HrEmployee::join('hr_educations','hr_educations.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','hr_educations.education_id')->where('hr_status_id',1)->whereIn('education_id', [32, 33, 46,134])->count();


        $allEmployees = HrEmployee::where('hr_status_id',1)->count();


       
        return view('dashboard.dashboard',compact('countAboveSixty','countBelowSixty','categoryA','categoryB','categoryC','allEmployees','pecRegisteredEngineers','associatedEngineers'));
    }


    public function testing()
    {
        return view('dashboard.dashboard1');
    }
}
