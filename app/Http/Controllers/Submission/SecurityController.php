<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Submission\Security;
use Illuminate\Support\Facades\File;
use App\Models\Common\Bank;
use App\Models\Common\Client;
use App\Models\Common\Partner;
use DB;
use DataTables;

class SecurityController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Security::orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->editColumn('type', function ($data) {
                    return $data->type == 'bid_security' ? 'Bid Security' : 'Performance Guarantee';
                })
                ->editColumn('bid_security_type', function ($data) {
                    return $data->bid_security_type == 'pay_order_cdr' ? 'Pay Order/CDR' : 'Bank Guarantee';
                })
                ->editColumn('date_issued', function ($data) {
                    return $data->date_issued ? $data->date_issued->format('Y-m-d') : '';
                })
                ->editColumn('expiry_date', function ($data) {
                    return $data->expiry_date ? $data->expiry_date->format('Y-m-d') : '';
                })
                ->editColumn('amount', function ($data) {
                    return $data->amount ? number_format($data->amount, 2) : '';
                })
                ->editColumn('status', function ($data) {
                    return $data->status == 'active' ? 'Active' : ($data->status == 'expired' ? 'Expired' : 'Released');
                })
                ->editColumn('client_id', function ($data) {
                    return $data->client->name ?? '';
                })
                ->editColumn('submitted_by', function ($data) {
                    return $data->submittedBy->name ?? '';
                })
                ->editColumn('bank_id', function ($data) {
                    return $data->bank->name ?? '';
                })
                ->editColumn('document_path', function ($data) {
                    return '<a href="' . asset($data->document_path) . '" target="_blank">View Document</a>';
                })

                ->addColumn('edit', function ($data) {

                    if (Auth::user()->hasPermissionTo('sub edit record')) {

                        $button = '<a class="btn btn-success btn-sm" href="' . route('security.edit', $data->id) . '"  title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>';

                        return $button;
                    }
                })
                ->addColumn('delete', function ($data) {
                    if (Auth::user()->hasPermissionTo('sub edit record')) {
                        $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteSecurity">Delete</a>';
                        return $button;
                    }
                })

                ->rawColumns(['edit', 'delete'])
                ->make(true);
        }

        $banks = Bank::select('name', 'id')->get();
        $clients = Client::select('name', 'id')->get();
        $partners = Partner::select('name', 'id')->get();

        return view('submission.security.create', compact('banks', 'clients', 'partners'));
    }

    public function store(Request $request)
    {

        $input = $request->all();

        if ($request->filled('date_issued')) {
            $input['date_issued'] = \Carbon\Carbon::parse($request->date_issued)->format('Y-m-d');
        }
        if ($request->filled('expiry_date')) {
            $input['expiry_date'] = \Carbon\Carbon::parse($request->expiry_date)->format('Y-m-d');
        }

        DB::transaction(function () use ($request, $input) {

            // Only document Avaiable
            if ($request->hasFile('document')) {
                $extension = request()->document->getClientOriginalExtension();

                $fileName =  time() . '.' . $extension;
                $folderName = "security/"  . "/";
                //store file
                $request->file('document')->storeAs('public/' . $folderName, $fileName);

                $file_path = storage_path('app/public/' . $folderName . $fileName);

                $input['document_path'] = $folderName . $fileName;

                //check create new data or update data
                if ($request->security_id) {
                    //update record
                    $security = Security::findOrFail($request->security_id);
                    $path = public_path('storage/' . $security->document_path);
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                    $security->update($input);
                } else {

                    //create record
                    $security = Security::create($input);
                }
            } else {

                $security = Security::findOrFail($request->security_id);
                $security->update($input);
            }
        });  //end transaction


        return response()->json(['status' => 'OK', 'message' => "Document Successfully Saved"]);
    }



    public function edit($id)
    {
        $security = Security::find($id);
        return response()->json($security);
    }

    public function destroy($id)
    {

        DB::transaction(function () use ($id) {

            $security = Security::findOrFail($id);

            $path = public_path('storage/' . $security->path);

            $security->forceDelete();

            if (File::exists($path)) {
                File::delete($path);
            }
        });  //end transaction


        return response()->json(['status' => 'OK', 'message' => "Data Successfully Deleted"]);
    }
}
