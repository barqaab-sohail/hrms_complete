<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrPromotion;
use App\Models\Hr\HrSalary;
use App\Models\Hr\HrDesignation;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDepartment;
use DB;

class PromotionController extends Controller
{
    
	public function create (Request $request){

		$salaries = HrSalary::all();
		$designations = HrDesignation::all();
		$managers = HrEmployee::all();
		$departments = HrDepartment::all();
		

	    	$hrPromotions =  HrPromotion::where('hr_employee_id', session('hr_employee_id'))->get();

	        if($request->ajax()){
	            return view('hr.promotion.create', compact('salaries','designations','managers','departments'));
	        }else{
	            return back()->withError('Please contact to administrator, SSE_JS');
	        }
    }

    public function store (Request $request){





    }

}
