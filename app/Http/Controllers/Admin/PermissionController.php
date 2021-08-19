<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Permission;
use App\Models\Hr\HrEmployee;
use App\Models\Admin\ModelHasPermission;
use App\Http\Requests\Admin\PermissionStore;
use App\User;

use DB;

class PermissionController extends Controller
{
    
    public function index(){

    	$permissionIds = Permission::all();

    	return view ('admin.permission.index', compact('permissionIds'));

    }

    public function store (PermissionStore $request){

    	DB::transaction(function () use ($request) { 
    	
	    	Permission::create(['name'=>$request->name, 'guard_name'=>'web']);
	    	

    	}); // end transcation

    	 return back()->with('message', 'Data Successfully Saved');
    }




    public function destroy($id){

    	
    	DB::transaction(function () use ($id) { 
    	
	    	Permission::findOrFail($id)->delete();
	    	ModelHasPermission::where('permission_id',$id)->delete();

    	}); // end transcation

       return back()->with('message', 'Data Successfully Deleted');
    }

    public function search(){

        $employees = HrEmployee::all();

        return view ('admin.employeePermission.search',compact('employees'));
    }


    public function result(Request $request){
        
        // foreach ($permissions as $permission){
        //     dd($permission->name);

        // }

         //dd($permissions);
        if($request->filled('employee')){
        $employee = HrEmployee::find($request->employee);
        $user = User::where('id', $employee->user_id)->first();
        $result = $user->getAllPermissions();
        return view('admin.employeePermission.result',compact('result'));
        }

        
    }

}
