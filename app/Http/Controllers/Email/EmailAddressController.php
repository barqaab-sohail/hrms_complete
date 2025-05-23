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
    }

    /**
     * Store new email address
     */
    public function store(Request $request, $type, $id)
    {
        $model = $this->getModel($type, $id);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:email_addresses,email',
            'type' => 'required|in:company,project,personal',
            'is_active' => 'sometimes|boolean',
            'is_primary' => 'sometimes|boolean',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // If setting as primary, unset any existing primary
        if ($request->is_primary) {
            EmailAddress::where('emailable_id', $model->id)
                ->where('emailable_type', get_class($model))
                ->update(['is_primary' => false]);
        }

        $model->emailAddresses()->create([
            'email' => $request->email,
            'type' => $request->type,
            'is_active' => $request->has('is_active'),
            'is_primary' => $request->has('is_primary'),
            'description' => $request->description,
            'created_by' => auth()->id()
        ]);

        return redirect()->route('emails.index', [$type, $id])
            ->with('success', 'Email address added successfully.');
    }

    /**
     * Show form to edit email
     */
    public function edit($type, $id, $emailId)
    {
        $model = $this->getModel($type, $id);
        $email = $model->emailAddresses()->findOrFail($emailId);

        return view('emails.edit', compact('model', 'email', 'type'));
    }

    /**
     * Update email address
     */
    public function update(Request $request, $type, $id, $emailId)
    {
        $model = $this->getModel($type, $id);
        $email = $model->emailAddresses()->findOrFail($emailId);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:email_addresses,email,' . $emailId,
            'type' => 'required|in:company,project,personal',
            'is_active' => 'sometimes|boolean',
            'is_primary' => 'sometimes|boolean',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // If setting as primary, unset any existing primary
        if ($request->is_primary && !$email->is_primary) {
            EmailAddress::where('emailable_id', $model->id)
                ->where('emailable_type', get_class($model))
                ->where('id', '!=', $emailId)
                ->update(['is_primary' => false]);
        }

        $email->update([
            'email' => $request->email,
            'type' => $request->type,
            'is_active' => $request->has('is_active'),
            'is_primary' => $request->has('is_primary'),
            'description' => $request->description,
            'updated_by' => auth()->id()
        ]);

        return redirect()->route('emails.index', [$type, $id])
            ->with('success', 'Email address updated successfully.');
    }

    /**
     * Delete email address
     */
    public function destroy($type, $id, $emailId)
    {
        $model = $this->getModel($type, $id);
        $email = $model->emailAddresses()->findOrFail($emailId);

        $email->delete();

        return redirect()->route('emails.index', [$type, $id])
            ->with('success', 'Email address deleted successfully.');
    }

    /**
     * Get the appropriate model based on type
     */
    protected function getModel($type, $id)
    {
        switch ($type) {
            case 'employee':
                return HrEmployee::findOrFail($id);
            case 'project':
                return PrDetail::findOrFail($id);
            default:
                abort(404, 'Invalid type specified');
        }
    }

    public function type($type)
    {
        if ($type == 'employee') {
            return HrEmployee::with('employeeCurrentDesignation')
                ->get()
                ->pluck('full_name_with_employee_id_and_designation', 'id');
        } elseif ($type == 'project') {
            return PrDetail::pluck('name', 'id');
        } else {
            return response()->json(['error' => 'Invalid type'], 400);
        }
    }
}
