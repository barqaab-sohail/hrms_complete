<?php

use App\Models\Hr\HrEmployee;
use App\Models\Hr\EmployeeManager;
use App\Models\Hr\EmployeeDepartment;
use App\Charts\Hr\DepartmentChart;
use App\Models\Office\Office;


function ageChart(){

	 	
	 	$countBelowForty= HrEmployee::where('date_of_birth','>=',\Carbon\Carbon::now()->subYears(40))->whereIn('hr_status_id',array(1,5))->count();

	 	$countBelowFifty= HrEmployee::where('date_of_birth','>=',\Carbon\Carbon::now()->subYears(50))->whereIn('hr_status_id',array(1,5))->count() - $countBelowForty;

	 	$countBelowSixty= HrEmployee::where('date_of_birth','>=',\Carbon\Carbon::now()->subYears(60))->whereIn('hr_status_id',array(1,5))->count() - $countBelowForty - $countBelowFifty;

	 	$countBelowSeventy= HrEmployee::where('date_of_birth','>=',\Carbon\Carbon::now()->subYears(70))->whereIn('hr_status_id',array(1,5))->count() - $countBelowForty - $countBelowFifty - $countBelowSixty;

	 	$countAboveSeventy= HrEmployee::whereIn('hr_status_id',array(1,5))->count()-$countBelowForty-$countBelowFifty - $countBelowSixty - $countBelowSeventy;
	 	
        return ['countBelowForty'=>$countBelowForty, 'countBelowFifty'=>$countBelowFifty, 'countBelowSixty'=>$countBelowSixty,'countBelowSeventy'=>$countBelowSeventy,'countAboveSeventy'=>$countAboveSeventy];
}


function categoryChart(){

		$categoryA = HrEmployee::join('employee_appointments','employee_appointments.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','employee_appointments.hr_category_id')->where('hr_category_id',1)->whereIn('hr_status_id',array(1,5))->count();
        $categoryB = HrEmployee::join('employee_appointments','employee_appointments.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','employee_appointments.hr_category_id')->where('hr_category_id',2)->whereIn('hr_status_id',array(1,5))->count();
        $categoryC = HrEmployee::join('employee_appointments','employee_appointments.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','employee_appointments.hr_category_id')->where('hr_category_id',3)->whereIn('hr_status_id',array(1,5))->count();

    return ['categoryA'=>$categoryA, 'categoryB'=>$categoryB, 'categoryC'=>$categoryC];
}

function engineerChart(){

		$pecRegisteredEngineers = HrEmployee::join('hr_memberships','hr_memberships.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','hr_memberships.membership_no')->whereIn('hr_status_id',array(1,5))->count();
        $associatedEngineers = HrEmployee::join('hr_educations','hr_educations.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','hr_educations.education_id')->whereIn('hr_status_id',array(1,5))->whereIn('education_id', [32, 33, 46,134])->count();
        $allEmployees = HrEmployee::whereIn('hr_status_id',array(1,5))->count();

        return ['pecRegisteredEngineers'=>$pecRegisteredEngineers, 'associatedEngineers'=>$associatedEngineers, 'allEmployees'=>$allEmployees];

}


function departmentChart(){

	$result = collect(HrEmployee::join('employee_departments','employee_departments.hr_employee_id','=','hr_employees.id')->select('hr_employees.*','employee_departments.hr_department_id','employee_departments.effective_date')->whereIn('hr_status_id',array(1,5))->orderBy('effective_date','desc')->get());
	$resultUnique = ($result->unique('id'));
	$resultUnique->values()->all();
	$finance = $resultUnique->where('hr_department_id',1)->count();
	$power = $resultUnique->where('hr_department_id',2)->count();
	$water = $resultUnique->where('hr_department_id',3)->count();

	return ['finance'=>$finance, 'power'=>$power, 'water'=>$water];

}