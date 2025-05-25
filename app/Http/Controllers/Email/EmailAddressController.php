<?php

namespace App\Http\Controllers\Email;

use App\Models\Email\EmailAddress;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Project\PrDetail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DB;
use DataTables;

class EmailAddressController extends Controller
{
    /**
     * Display emails for an entity
     */
    public function index()
    {

        $emails = EmailAddress::all();

        return view('emails.index', compact('emails'));
    }

    /**
     * Show form to create new email
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            // This should return the DataTables data
            $data = EmailAddress::all();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Edit', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editEmail">Edit</a>';
                    return $btn;
                })
                ->addColumn('Delete', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteEmail">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['Edit', 'Delete'])
                ->make(true);
        }

        // For non-AJAX requests (regular page load)
        return view('emails.create');
    }

    // Add a new method specifically for typeahead data
    public function getTypeaheadData($type)
    {
        return $this->emailType($type);
    }



    public function emailType($type)
    {
        if ($type == 'employee') {
            return HrEmployee::with('employeeCurrentDesignation')
                ->get()
                ->pluck('full_name_with_employee_id_and_designation', 'id');
        } elseif ($type == 'project') {
            return PrDetail::pluck('name', 'id');
        } else {
            return response()->json(['error' => 'Invalid typeee'], 400);
        }
    }
}
