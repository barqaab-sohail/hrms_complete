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

    public function index()
    {

        $permissionIds = Permission::all();

        return view('admin.permission.index', compact('permissionIds'));
    }

    public function store(PermissionStore $request)
    {

        DB::transaction(function () use ($request) {
            \Artisan::call('cache:clear');
            Permission::create(['name' => $request->name, 'guard_name' => 'web']);
        }); // end transcation

        return back()->with('message', 'Data Successfully Saved');
    }



    //this function is used for delete specific permissions.  This function is not used for employee permission delete
    public function destroy($id)
    {


        DB::transaction(function () use ($id) {

            Permission::findOrFail($id)->delete();
            ModelHasPermission::where('permission_id', $id)->delete();
        }); // end transcation

        return back()->with('message', 'Data Successfully Deleted');
    }

    public function search()
    {

        $employees = HrEmployee::with('employeeDesignation')->get();

        return view('admin.employeePermission.search', compact('employees'));
    }


    public function result(Request $request)
    {

        if ($request->filled('employee')) {
            $employee = HrEmployee::find($request->employee);
            $user = User::where('id', $employee->user_id)->first();
            $result = $user->getAllPermissions();
            $userId = $user->id;

            return view('admin.employeePermission.result', compact('result', 'userId'));
        }
    }


    public function employeePermissionDestroy($id, $userId)
    {
        $user = User::find($userId);
        $user->revokePermissionTo($id);
        return response()->json(['status' => 'OK', 'message' => "Employee Permission Successfully Deleted"]);
    }

    public function userList()
    {
        $modelsHasPermissions = ModelHasPermission::with('user.hrEmployee', 'permission')->get();
        $distintModelPermission = ModelHasPermission::with('user.hrEmployee', 'permission')->groupBy('model_id')->get();
        $employees = HrEmployee::with('user')->get();
        $permissions = Permission::all();
        return view('admin.permission.userList', compact('modelsHasPermissions', 'employees', 'permissions','distintModelPermission'));
    }

    public function userPermissionDestroy($permissionName, $userId)
    {
        $user = User::find($userId);
        $user->revokePermissionTo($permissionName);
        return redirect()->back()->with('message', 'Employee Permission Successfully Deleted');
    }

    public function addPermission(Request $request)
    {
        $employee = HrEmployee::find($request->hr_employee_id);
        $userAllPermissions = $employee->user->getPermissionNames()->toArray();
        if (in_array($request->permission_name,  $userAllPermissions)) {
            return redirect()->back()->withError('Permission Already Taken');
        }else
        {
            $employee->user->givePermissionTo($request->permission_name);
            return redirect()->back()->with('message', 'Employee Permission Successfully Added');

        }

       // return 'ok';
        
    }

    public function userAllPermissionDelete(Request $request){

        $employee = HrEmployee::find($request->hr_employee_id);
        $userAllPermissions = $employee->user->getPermissionNames()->toArray();
        if($userAllPermissions){
            foreach ($userAllPermissions as $userPermission){

                $employee->user->revokePermissionTo($userPermission);
            }
            
            return redirect()->back()->with('message', "$employee->full_name  All Permissions Removed Successfully");
        }else{
            return redirect()->back()->with('message', "$employee->full_name  have currently no permission");
        }
    }
}
