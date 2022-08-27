<?php

namespace App\Http\Controllers\Project\Progress;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\Progress\PrSubProject;

class SubProjectController extends Controller
{
    public function index()
    {

        $prSubProject = PrSubProject::where('pr_detail_id', session('pr_detail_id'))->get();

        $view =  view('project.progress.subProject.create', compact('prSubProject'))->render();
        return response()->json($view);
    }

    public function create(Request $request)
    {

        if ($request->ajax()) {
            $data = PrSubProject::where('pr_detail_id', session('pr_detail_id'))->latest()->get();

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

        $input['pr_detail_id'] = session('pr_detail_id');

        DB::transaction(function () use ($input, $request) {

            PrSubProject::updateOrCreate(
                ['id' => $input['pr_sub_project_id']],
                [
                    'pr_detail_id' => $input['pr_detail_id'],
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
