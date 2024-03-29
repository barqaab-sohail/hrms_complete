<?php

namespace App\Http\Controllers\Project\Progress;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\Progress\PrProgressActivity;
use App\Http\Requests\Project\Progress\ActivityStore;
use App\Models\Project\Progress\PrSubTotalWeightage;
use DB;
use DataTables;

class ActivitiesController extends Controller
{
    public function index()
    {

        $prProgressActivities = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->get();

        $view =  view('project.progress.activities.create', compact('prProgressActivities'))->render();
        return response()->json($view);
    }

    public function create(Request $request)
    {

        if ($request->ajax()) {
            $data = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editActivity">Edit</a>';

                    return $btn;
                })
                ->addColumn('Delete', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteActivity">Delete</a>';


                    return $btn;
                })
                ->editColumn('weightage', function ($row) {
                    return round($row->weightage, 2);
                })
                ->addColumn('total_weightage', function ($row) {
                    return $row->prSubTotalWeightage->total_weightage ?? '';
                })

                ->rawColumns(['Edit', 'Delete', 'total_weightage'])
                ->make(true);
        }


        $prProgressActivities = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->get();
        $view =  view('project.progress.activities.create', compact('prProgressActivities'))->render();
        return response()->json($view);
    }

    public function store(ActivityStore $request)
    {

        $input = $request->all();

        $input['pr_detail_id'] = session('pr_detail_id');

        if (!$request->belong_to_activity) {
            $input['belong_to_activity'] = null;
        }

        DB::transaction(function () use ($input, $request) {

            $prProgressActivity = PrProgressActivity::updateOrCreate(
                ['id' => $input['activity_id']],
                [
                    'pr_detail_id' => $input['pr_detail_id'],
                    'level' => $input['level'],
                    'belong_to_activity' => $input['belong_to_activity'],
                    'name' => $input['name'],
                    'weightage' => $input['weightage']

                ]
            );

            if ($request->filled('total_weightage')) {

                PrSubTotalWeightage::updateOrCreate(
                    ['pr_progress_activity_id' => $prProgressActivity->id],
                    [
                        'pr_progress_activity_id' => $prProgressActivity->id,
                        'total_weightage' => $input['total_weightage']
                    ]
                );
            }
        }); // end transcation

        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {

        $prProgressActivity = PrProgressActivity::find($id);
        $prProgressActivity->total_weightage = $prProgressActivity->prSubTotalWeightage->total_weightage ?? '';
        //$prProgressActivity->prSubTotalWeightage->total_weightage ?? ''
        return response()->json($prProgressActivity);
    }



    public function destroy($id)
    {

        PrProgressActivity::findOrFail($id)->delete();
        return response()->json(['status' => 'OK', 'message' => "Data Successfully Deleted"]);
    }

    public function mainActivities($level)
    {

        $activities = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', "<", $level)->get();
        return response()->json($activities);
    }
}
