<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\MisUser;
use DB;
use DataTables;

class MisUserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::all();
            return DataTables::of($data)
                ->addColumn('full_name', function ($data) {
                    return $data->hrEmployee->full_name ?? '';
                })
                ->editColumn('is_allow_mis', function ($data) {
                    if (!isAllowMis($data->id ?? 0)) {
                        $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-mis-user="false" data-original-title="edit mis rights" class="btn btn-danger btn-sm editMisUser">Not Allowed</a>';
                        return $button;
                    } else {
                        $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-mis-user="true" data-original-title="edit mis rights" class="btn btn-success btn-sm editMisUser">MIS Allowed</a>';
                        return $button;
                    }
                })
                ->rawColumns(['full_name', 'is_allow_mis'])
                ->make(true);
        }
        $users = User::all();
        return view('admin.misUser.list', compact('users'));
    }

    public function store(Request $request)
    {
        $user = User::find($request->userId);
        $status = $request->isAllowMis === "true" ? false : true;
        $request->isAllowMis === false ? true : true;
        $misUser = MisUser::where('user_id', $user->id)->first();
        MisUser::updateOrCreate(['id' => $misUser->id ?? ''], ['user_id' => $request->userId, 'is_allow_mis' => $status]);
        return response()->json(['status' => 'OK', 'message' => "Successfully Updated"]);
    }
}
