<?php

namespace App\Http\Controllers\Email;

use App\Models\Email\EmailAddress;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Project\PrDetail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Email\EmailAddressStore;
use App\Models\Hr\HrDepartment;
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
                ->addColumn('emailable_id', function ($row) {
                    if ($row->emailable_type == 'employee') {
                        return $row->emailable->full_name_with_employee_id_and_designation;
                    } elseif ($row->emailable_type == 'project') {
                        return $row->emailable->name;
                    } elseif ($row->emailable_type == 'department') {
                        return $row->emailable->name;
                    }
                })
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


    public function store(EmailAddressStore $request)
    {


        DB::transaction(function () use ($request) {

            EmailAddress::updateOrCreate(
                ['id' => $request['email_id']],
                [
                    'email' => $request['email'],
                    'type' => $request['type'],
                    'is_active' => $request['is_active'] ?? true,
                    'is_primary' => $request['is_primary'] ?? false,
                    'description' => $request['description'] ?? null,
                    'emailable_id' => $request['emailable_id'],
                    'emailable_type' => $request['emailable_type'],

                ]
            );
        });  //end transaction


        return response()->json(['status' => 'OK', 'message' => "Email Successfully Saved"]);
    }

    public function edit($id)
    {
        try {
            $emailAddress = EmailAddress::with('emailable')->findOrFail($id);
            return response()->json($emailAddress);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Record not found'], 404);
        }
    }

    public function destroy(Request $request, $id)
    {

        DB::transaction(function () use ($id) {
            EmailAddress::find($id)->delete();
        }); // end transcation 
        return response()->json(['message' => 'data  delete successfully.']);
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
        } elseif ($type == 'department') {
            return HrDepartment::pluck('name', 'id');
        } else {
            return response()->json(['error' => 'Invalid typeee'], 400);
        }
    }
}
