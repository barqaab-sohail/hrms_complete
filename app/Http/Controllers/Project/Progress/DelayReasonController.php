<?php

namespace App\Http\Controllers\Project\Progress;

use DB;
use DataTables;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Project\PrDetail;
use App\Http\Controllers\Controller;
use App\Models\Project\Progress\PrDelayReason;
use App\Models\Project\Contractor\PrContractor;
use App\Http\Requests\Project\Progress\DelayReasonStore;

class DelayReasonController extends Controller
{
    public function show($prDetailId)
    {

        $prContractors = PrContractor::where('pr_detail_id', $prDetailId)->get();
        $prDetail = PrDetail::find($prDetailId);
        $view =  view('project.progress.delayReason.create', compact('prContractors', 'prDetail'))->render();
        return response()->json($view);
    }

    public function create(Request $request)
    {

        if ($request->ajax()) {
            $data = PrDelayReason::where('pr_detail_id', $request->prDetailId)->latest()->get();

            return DataTables::of($data)

                ->editColumn('contract_name', function ($row) {
                    return $row->prContractor->contract_name ?? '';
                })
                ->editColumn('reason', function ($row) {
                    return Str::of($row->reason)->limit(300);
                })
                ->editColumn('month', function ($row) {
                    return \Carbon\Carbon::parse($row->month)->format('F-Y');
                })
                ->addColumn('edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editDelayReason">Edit</a>';

                    return $btn;
                })
                ->addColumn('delete', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteDelayReason">Delete</a>';

                    return $btn;
                })

                ->rawColumns(['edit', 'delete'])
                ->make(true);
        }
    }

    public function store(DelayReasonStore $request)
    {

        $input = $request->all();

        DB::transaction(function () use ($input, $request) {

            PrDelayReason::updateOrCreate(['id' => $input['delay_reason_id']], $input);
        }); // end transcation

        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($Id)
    {
        $data = PrDelayReason::find($Id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        PrDelayReason::findOrFail($id)->delete();
        return response()->json(['status' => 'OK', 'message' => "Data Successfully Deleted"]);
    }
}
