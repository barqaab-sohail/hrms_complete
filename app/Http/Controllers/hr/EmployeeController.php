<?php

namespace App\Http\Controllers\hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Gender;
use App\Models\Common\MaritalStatus;
use App\Models\Common\Religion;
use App\Models\Hr\HrEmployee;
use DB;
use App\Http\Requests\Hr\EmployeeStore;


class EmployeeController extends Controller
{
    public function create(){

    	$genders = Gender::all();
    	$maritalStatuses = MaritalStatus::all();
    	$religions = Religion::all();

    	return view ('hr.employee.create', compact('genders','maritalStatuses','religions'));
    }


    public function store (EmployeeStore $request){

    	 $input = $request->all();
            if($request->filled('date_of_birth')){
            $input ['date_of_birth']= \Carbon\Carbon::parse($request->date_of_birth)->format('Y-m-d');
            }
            if($request->filled('cnic_expiry')){
            $input ['cnic_expiry']= \Carbon\Carbon::parse($request->cnic_expiry)->format('Y-m-d');
            }

    	DB::transaction(function () use ($input) {  

    		HrEmployee::create($input);

    	}); // end transcation



    	return $data;
    }

    public function index(){
    	$employees = HrEmployee::all();
    	return view ('hr.employee.list',compact('employees'));
    }


    public function edit($id){
    	$genders = Gender::all();
    	$maritalStatuses = MaritalStatus::all();
    	$religions = Religion::all();
    	$data = HrEmployee::where('id',$id)->get()->first();

    	return view ('hr.employee.edit', compact('genders','maritalStatuses','religions','data'));
    }


    public function update(Request $request, $id){

    	$input = $request->all();
            if($request->filled('date_of_birth')){
            $input ['date_of_birth']= \Carbon\Carbon::parse($request->date_of_birth)->format('Y-m-d');
            }
            if($request->filled('cnic_expiry')){
            $input ['cnic_expiry']= \Carbon\Carbon::parse($request->cnic_expiry)->format('Y-m-d');
            }

    		DB::transaction(function () use ($input, $id) {  

    		HrEmployee::findOrFail($id)->update($input);

    		}); // end transcation

    	return 'OK';
    }


}
