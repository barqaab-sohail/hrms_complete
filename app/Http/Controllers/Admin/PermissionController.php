<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Permission;
use App\Models\Admin\ModelHasPermission;
use App\Http\Requests\Admin\PermissionStore;

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

    	 return back()->with('message', 'Data Sucessfully Saved');
    }




    public function destroy($id){

    	
    	DB::transaction(function () use ($id) { 
    	
	    	Permission::findOrFail($id)->delete();
	    	ModelHasPermission::where('permission_id',$id)->delete();

    	}); // end transcation

       return back()->with('message', 'Data Sucessfully Deleted');
    }

}
