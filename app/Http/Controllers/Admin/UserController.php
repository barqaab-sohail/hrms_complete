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
                    return $data->hrEmployee->full_name ?? '';
                })
                ->make(true);
        }
        $users = User::all();
        $employees = HrEmployee::select('id', 'first_name', 'last_name', 'employee_no', 'hr_status_id')->where('hr_status_id', 1)->get();
        return view('admin.user.list', compact('users', 'employees'));
    }

    public function store(Request $request)
    {
        $input = $request->all();


        DB::transaction(function () use ($input, $request) {
            $input =  User::updateOrCreate(
                ['id' => $request->user_id],
                $input
            );
        }); // end transcation


        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {
        $data = User::find($id);
        return response()->json($data);
    }



    public function destroy($id)
    {

        User::findOrFail($id)->delete();

        return response()->json(['status' => 'OK', 'message' => 'Data Successfully Deleted']);
    }
}
