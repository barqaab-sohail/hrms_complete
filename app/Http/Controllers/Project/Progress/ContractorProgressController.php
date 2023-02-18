<?php

namespace App\Http\Controllers\Project\Progress;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\Progress\PrActualVsSchedule;
use App\Models\Project\Contractor\PrContractor;
use App\Http\Requests\Project\Progress\ActualVsScheduleStore;
use DB;
use DataTables;

class ContractorProgressController extends Controller
{
    public function index()
    {

        $prContractors = PrContractor::where('pr_detail_id', session('pr_detail_id'))->get();
        $view =  view('project.progress.actualSchedule.create', compact('prContractors'))->render();
        return response()->json($view);
    }

    public function create(Request $request)
    {

        if ($request->ajax()) {
            $data = PrActualVsSchedule::where('pr_detail_id', session('pr_detail_id'))->latest()->get();

            return DataTables::of($data)

                ->editColumn('contractor_name', function ($row) {
                    return $row->prContractor->contractor_name ?? '';
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
        $input['pr_detail_id'] = session('pr_detail_id');


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
