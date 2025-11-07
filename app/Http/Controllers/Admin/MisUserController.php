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
                    return $data->hrEmployee->employee_no . '-' . $data->hrEmployee->full_name . '-' . $data->hrEmployee->employeeCurrentDesignation->name;
                })
                ->editColumn('is_allow_mis', function ($data) {
                    if (MisUser::isAllowMis($data->id )) {
                        $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-mis-user="true" data-original-title="edit mis rights" class="btn btn-success btn-sm editMisUser">Allowed</a>';
                        return $button;
                    } else {
                        $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-mis-user="false" data-original-title="edit mis rights" class="btn btn-danger btn-sm editMisUser">Not Allowed</a>';
                        return $button;

                    }
                })
                ->editColumn('is_allow_managment_access', function ($data) {
                    if ( MisUser::isAllowManagement($data->id)) {
                        $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-management-user="true" data-original-title="edit Management rights" class="btn btn-success btn-sm editManagementUser">Allowed</a>';
                        return $button;
                    } else {
                        $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-management-user="false" data-original-title="edit Management rights" class="btn btn-danger btn-sm editManagementUser">Not Allowed</a>';
                        return $button;
                        
                    }
                })
                ->rawColumns(['full_name', 'is_allow_mis','is_allow_managment_access'])
                ->make(true);
        }
    }

    public function create()
    {
        $users = User::all();
        return view('admin.misUser.list', compact('users'));
    }

    public function store(Request $request)
    {
          if ($request->has('isAllowMis')) {
            MisUser::toggleMisAccess($request->userId);
            return response()->json(['status' => 'OK', 'message' => "MIS Successfully Updated"]);
        } elseif ($request->has('isAllowManagement')) {
            MisUser::toggleManagementAccess($request->userId);     
            return response()->json(['status' => 'OK', 'message' => "Management Successfully Updated"]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'No access type specified'], 400);
        }
    }
}
