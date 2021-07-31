<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\AuditResultStore;
use App\Models\Admin\Audit;
use App\Models\Hr\EmployeeAppointment;
use App\User;


class AuditController extends Controller
{
    public function search(){
    	$users = User::all();
        return view ('admin.audit.search',compact('users'));
    }


    public function result(AuditResultStore $request){

    	// $EmployeeAppointment = EmployeeAppointment::first();
    	// $result = $EmployeeAppointment->audits()->with('user')->get();
    	// return view('admin.audit.searchResult',compact('result'));

	  	if($request->filled('user')){
	  		$result = Audit::where('user_id',$request->user)->latest()->take($request->total_records)->get();
	  		return view('admin.audit.searchResult',compact('result'));
	  	}

    }
}
