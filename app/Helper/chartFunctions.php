<?php

use App\Models\Hr\HrEmployee;
use App\Models\Hr\EmployeeManager;
use App\Models\Hr\EmployeeDepartment;
use App\Charts\Hr\DepartmentChart;
use App\Models\Common\Office;


function ageChart(){

	 	
	 	$countBelowForty= HrEmployee::where('date_of_birth','>=',\Carbon\Carbon::now()->subYears(40))->where('hr_status_id',1)->count();

	 	$countBelowFifty= HrEmployee::where('date_of_birth','>=',\Carbon\Carbon::now()->subYears(50))->where('hr_status_id',1)->count() - $countBelowForty;

	 	$countBelowSixty= HrEmployee::where('date_of_birth','>=',\Carbon\Carbon::now()->subYears(60))->where('hr_status_id',1)->count() - $countBelowForty - $countBelowFifty;

	 	$countBelowSeventy= HrEmployee::where('date_of_birth','>=',\Carbon\Carbon::now()->subYears(70))->where('hr_status_id',1)->count() - $countBelowForty - $countBelowFifty - $countBelowSixty;

	 	$countAboveSeventy= HrEmployee::where('hr_status_id',1)->count()-$countBelowForty-$countBelowFifty - $countBelowSixty - $countBelowSeventy;
	 	
        return ['countBelowForty'=>$countBelowForty, 'countBelowFifty'=>$countBelowFifty, 'countBelowSixty'=>$countBelowSixty,'countBelowSeventy'=>$countBelowSeventy,'countAboveSeventy'=>$countAboveSeventy];
}


function categoryChart(){

		$categoryA = HrEmployee::join('employee_appointments','employee_appointments.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','employee_appointments.hr_category_id')->where('hr_category_id',1)->where('hr_status_id',1)->count();
        $categoryB = HrEmployee::join('employee_appointments','employee_appointments.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','employee_appointments.hr_category_id')->where('hr_category_id',2)->where('hr_status_id',1)->count();
        $categoryC = HrEmployee::join('employee_appointments','employee_appointments.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','employee_appointments.hr_category_id')->where('hr_category_id',3)->where('hr_status_id',1)->count();

    return ['categoryA'=>$categoryA, 'categoryB'=>$categoryB, 'categoryC'=>$categoryC];
}

function engineerChart(){

		$pecRegisteredEngineers = HrEmployee::join('hr_memberships','hr_memberships.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','hr_memberships.membership_no')->where('hr_status_id',1)->count();
        $associatedEngineers = HrEmployee::join('hr_educations','hr_educations.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','hr_educations.education_id')->where('hr_status_id',1)->whereIn('education_id', [32, 33, 46,134])->count();
        $allEmployees = HrEmployee::where('hr_status_id',1)->count();

        return ['pecRegisteredEngineers'=>$pecRegisteredEngineers, 'associatedEngineers'=>$associatedEngineers, 'allEmployees'=>$allEmployees];

}


function departmentChart(){

	$result = collect(HrEmployee::join('employee_departments','employee_departments.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','employee_departments.hr_department_id','employee_departments.effective_date')->where('hr_status_id',1)->orderBy('effective_date','desc')->get());
	$resultUnique = ($result->unique('id'));
	$resultUnique->values()->all();
	$finance = $resultUnique->where('hr_department_id',1)->count();
	$power = $resultUnique->where('hr_department_id',2)->count();
	$water = $resultUnique->where('hr_department_id',3)->count();

	return ['finance'=>$finance, 'power'=>$power, 'water'=>$water];

}