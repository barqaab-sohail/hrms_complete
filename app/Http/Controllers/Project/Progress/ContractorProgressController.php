<?php

namespace App\Http\Controllers\Project\Progress;

use DB;
use DataTables;
use Illuminate\Http\Request;
use App\Models\Project\PrDetail;
use App\Http\Controllers\Controller;
use App\Models\Project\Contractor\PrContractor;
use App\Models\Project\Progress\PrActualVsSchedule;
use App\Http\Requests\Project\Progress\ActualVsScheduleStore;

class ContractorProgressController extends Controller
{
    public function show($prDetailId)
    {

        $prContractors = PrContractor::where('pr_detail_id', $prDetailId)->get();
        $prDetail = PrDetail::find($prDetailId);
        $view =  view('project.progress.actualSchedule.create', compact('prContractors', 'prDetail'))->render();
        return response()->json($view);
    }

    public function create(Request $request)
    {

        if ($request->ajax()) {
            $data = PrActualVsSchedule::where('pr_detail_id', $request->prDetailId)->orderBy('month', 'asc')->get();

            return DataTables::of($data)

                ->editColumn('contract_name', function ($row) {
                    return $row->prContractor->contract_name ?? '';
                })
                ->editColumn('month', function ($row) {
                    return \Carbon\Carbon::parse($row->month)->format('F-Y');
                })
                ->addColumn('edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editActualSchedule">Edit</a>';

                    return $btn;
                })
                ->addColumn('delete', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteActualSchedule">Delete</a>';

                    return $btn;
                })

                ->rawColumns(['edit', 'delete'])
                ->make(true);
        }
    }

    public function store(ActualVsScheduleStore $request)
    {

        $input = $request->all();

        DB::transaction(function () use ($input, $request) {

            PrActualVsSchedule::updateOrCreate(['id' => $input['actual_schedule_id']], $input);
        }); // end transcation

        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($Id)
    {
        $data = PrActualVsSchedule::find($Id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        PrActualVsSchedule::findOrFail($id)->delete();
        return response()->json(['status' => 'OK', 'message' => "Data Successfully Deleted"]);
    }
}
