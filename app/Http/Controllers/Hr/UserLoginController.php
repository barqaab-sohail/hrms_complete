<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDocumentation;
use App\User;
use App\Permission;
use DB;

class UserLoginController extends Controller
{
   public function edit(Request $request, $id){
    	
    	$data = HrEmployee::find($id);
    	$user = User::where('id',$data->user_id)->first();
    	$permissions = Permission::all();
    	$picture = HrDocumentation::where([['hr_employee_id', '=',session('hr_employee_id')], ['description','=','picture'] ])->first();
	    
	   if($request->ajax()){
	   		$view = view('hr.login.create', compact('data','permissions','picture'))->render();
    		return response()->json($view);
	  }else
	  {
	  	return back()->withError('Please contact to administrator, SSE_JS');
	  }
	}

	public function store (Request $request){

		DB::transaction(function () use ($request) {  

			$employee = HrEmployee::find(session('hr_employee_id'));
			//Firs check Employee have user_id if yes than get user and give premission 
			//Else create User detail and updated user_id in Employee Table then give permission 
			if($employee->user_id??''){
				$user = User::where('id', $employee->user_id)->first();
			}else{
				
				$user = User::create(['email'=>$request->email, 'password'=>Hash::make(Str::random(8))]);
				$employee->update(['user_id' => $user->id]);
			}
			
			$user->givePermissionTo($request->permission);

		});

		return response()->json(['status'=> 'OK', 'message' => "Permission Successfully Saved"]);

	}

	public function destroy($id){
		$data = HrEmployee::find(session('hr_employee_id'));
		$user = User::where('id',$data->user_id)->first();
		$user->revokePermissionTo($id);
		return response()->json(['status'=> 'OK', 'message' => "Permission Successfully Deleted"]);

	}


	public function refreshTable(){
		$data = HrEmployee::find(session('hr_employee_id'));
    	$user = User::where('id',$data->user_id)->first();
		$userPermissions = $user->getAllPermissions();
        return view('hr.login.list',compact('userPermissions'));
    }

}
