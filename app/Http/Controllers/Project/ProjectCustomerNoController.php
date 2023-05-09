<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrDetail;
use App\Models\Project\PrCustomerNo;
use DB;
use DataTables;

class ProjectCustomerNoController extends Controller
{
    public function index()
    {

        $projects = PrDetail::whereNotIn('name', array('overhead'))->get();
        $view =  view('project.customerNo.create', compact('projects',))->render();
        return $view;
    }
    public function create(Request $request)
    {

        if ($request->ajax()) {

            $data = PrCustomerNo::all();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editProjectCustomerNo">Edit</a>';

                    return $btn;
                })
                ->addColumn('Delete', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteProjectCustomerNo">Delete</a>';


                    return $btn;
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
        $view =  view('project.customerNo.create', compact('projects',))->render();
        return response()->json($view);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pr_detail_id' => 'required|unique:pr_customer_nos,pr_detail_id,' . $request->customer_id,
            'customer_no' => 'required|max:12',
        ]);

        $input = $request->all();

        DB::transaction(function () use ($input) {
            PrCustomerNo::updateOrCreate(
                ['id' => $input['customer_id']],
                [
                    'pr_detail_id' => $input['pr_detail_id'],
                    'customer_no' => $input['customer_no']
                ]
            );
        }); // end transcation

        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {

        $prCustomerNo = PrCustomerNo::find($id);

        return response()->json($prCustomerNo);
    }

    public function destroy($id)
    {
        PrCustomerNo::findOrFail($id)->delete();
        return response()->json(['success' => "Data Successfully Deleted"]);
    }
}
