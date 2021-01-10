<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;

class HrChartController extends Controller
{
    public function  category(){
    	$categoryA = HrEmployee::join('hr_appointments','hr_appointments.hr_employee_id','=','hr_employees.id')->where('hr_status_id',1)
           ->where('hr_category_id',1)
           ->get()->count();

        $categoryB = HrEmployee::join('hr_appointments','hr_appointments.hr_employee_id','=','hr_employees.id')->where('hr_status_id',1)
           ->where('hr_category_id',2)
           ->get()->count();

        $categoryC = HrEmployee::join('hr_appointments','hr_appointments.hr_employee_id','=','hr_employees.id')->where('hr_status_id',1)
           ->where('hr_category_id',3)
           ->get()->count();

        $engs = HrEmployee::join('hr_memberships','hr_memberships.hr_employee_id','=','hr_employees.id')->where('hr_status_id',1)
           ->get()->count();

        $employees = HrEmployee::where('hr_status_id',1)->get()->count();
        

        return view('hr.charts.category', compact('categoryA','categoryB','categoryC','employees'));
    }
}
