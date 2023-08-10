<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\MisUser;
use App\Models\Hr\HrEmployee;
use DB;
use DataTables;

class UserController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::all();
            return DataTables::of($data)
                ->addColumn('full_name', function ($data) {
                    return $data->misEmployeeUser->full_name ??  $data->hrEmployee->full_name;
                })
                ->addColumn('edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editUser">Edit</a>';
                    return $btn;
                })
                ->addColumn('delete', function ($row) {


                    $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteUser">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['edit', 'delete'])
                ->make(true);
        }
        $users = User::all();
        $employees = HrEmployee::select('id', 'first_name', 'last_name', 'employee_no', 'hr_status_id')->where('hr_status_id', 1)->get();
        return view('admin.user.list', compact('users', 'employees'));
    }

    public function store(Request $request)
    {


        $validated = $request->validate([
            'hr_employee_id' => 'required',
            'email' => 'required|email|unique:users,email,' . $request->user_id,
        ]);


        $input = $request->all();
        DB::transaction(function () use ($input, $request) {
            $input =  User::updateOrCreate(
                ['id' => $request->user_id],
                $input
            );
            $user = User::where('id', $input->id)->first();

            MisUser::updateOrCreate(['user_id' => $user->id ?? ''], ['user_id' => $user->id, 'hr_employee_id' => $request->hr_employee_id]);
        }); // end transcation


        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {
        $data = User::with('misEmployeeUser', 'hrEmployee')->find($id);
        return response()->json($data);
    }



    public function destroy($id)
    {

        User::findOrFail($id)->delete();

        return response()->json(['status' => 'OK', 'message' => 'Data Successfully Deleted']);
    }
}
