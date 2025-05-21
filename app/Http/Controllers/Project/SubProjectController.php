<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project\PrDetail;
use Illuminate\Http\Request;
use App\Models\Project\PrSubProject;
use DB;
use DataTables;

class SubProjectController extends Controller
{
    public function show($prDetailId)
    {

        $prSubProject = PrSubProject::where('pr_detail_id', $prDetailId)->get();
        $prDetail = PrDetail::find($prDetailId);

        $view =  view('project.progress.subProject.create', compact('prSubProject', 'prDetail'))->render();
        return response()->json($view);
    }

    public function create(Request $request)
    {

        if ($request->ajax()) {
            $data = PrSubProject::where('pr_detail_id', $request->prDetailId)->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editSubProject">Edit</a>';

                    return $btn;
                })
                ->addColumn('Delete', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteSubProject">Delete</a>';
                    return $btn;
                })

                ->rawColumns(['Edit', 'Delete'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {

        $input = $request->all();


        DB::transaction(function () use ($input, $request) {

            PrSubProject::updateOrCreate(
                ['id' => $input['pr_sub_project_id']],
                [
                    'pr_detail_id' => $input['pr_detail_id'],
                    'name' => $input['name'],
                    'weightage' => $input['weightage']

                ]
            );
        }); // end transcation

        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {

        $prSubProject = PrSubProject::find($id);

        return response()->json($prSubProject);
    }

    public function destroy($id)
    {
        PrSubProject::findOrFail($id)->delete();
        return response()->json(['status' => 'OK', 'message' => "Data Successfully Deleted"]);
    }
}
