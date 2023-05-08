<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project\PrDetail;
use App\Models\Project\PrLedgerLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;
use DataTables;

class ProjectLedgerUrlController extends Controller
{
    public function index()
    {


        $projects = PrDetail::whereNotIn('name', array('overhead'))->get();
        $view =  view('project.ledgerUrl.create', compact('projects',))->render();
        return $view;
    }
    public function create(Request $request)
    {

        if ($request->ajax()) {

            $data = PrLedgerLink::all();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editProjectUrl">Edit</a>';

                    return $btn;
                })
                ->addColumn('Delete', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteProjectUrl">Delete</a>';


                    return $btn;
                })
                ->editColumn('url', function ($row) {
                    return Str::limit($row->url, 45);
                })
                ->addColumn('pr_detail_id', function ($row) {

                    $project = PrDetail::find($row->pr_detail_id);
                    return $project->name;
                    //employeeFullName($row->pr_detail_id);

                })


                ->rawColumns(['Edit', 'Delete', 'pr_detail_id'])
                ->make(true);
        }

        $projects = PrDetail::whereNotIn('name', array('overhead'))->get();
        $view =  view('project.ledgerUrl.create', compact('projects',))->render();
        return response()->json($view);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pr_detail_id' => 'required|unique:pr_ledger_links,pr_detail_id,' . $request->link_id,
            'url' => 'required|max:511',
        ]);

        $input = $request->all();

        DB::transaction(function () use ($input) {
            PrLedgerLink::updateOrCreate(
                ['id' => $input['link_id']],
                [
                    'pr_detail_id' => $input['pr_detail_id'],
                    'url' => $input['url']
                ]
            );
        }); // end transcation

        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {

        $prLedgerLink = PrLedgerLink::find($id);

        return response()->json($prLedgerLink);
    }

    public function destroy($id)
    {
        PrLedgerLink::findOrFail($id)->delete();
        return response()->json(['success' => "Data Successfully Deleted"]);
    }
}
