<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\DirectCostDescription;
use App\Models\Project\Invoice\DirectCostDetail;
use Illuminate\Validation\Rule;
use DB;
use DataTables;

class DirectCostDetailController extends Controller
{
    public function index()
    {
        $directCostDescription = DirectCostDescription::all();
        $view =  view('project.direct_cost_detail.create', compact('directCostDescription'))->render();
        return response()->json($view);
    }



    public function create(Request $request)
    {

        if ($request->ajax()) {

            $data =  DirectCostDetail::where('pr_detail_id', session('pr_detail_id'))->get();

            return DataTables::of($data)

                ->editColumn('direct_cost_description_id', function ($row) {

                    return $row->directCostDescription->name;
                })

                ->addColumn('edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editDetail">Edit</a>';
                    return $btn;
                })
                ->addColumn('delete', function ($row) {


                    $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteDetail">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['edit', 'delete'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'direct_cost_description_id' => ['required', Rule::unique('direct_cost_details')->where(fn ($query) => $query->where('pr_detail_id', session('pr_detail_id'))->where('id', '!=', $request->direct_cost_detail_id))],
            'amount' => ['required']
        ]);

        $input = $request->all();
        $input['pr_detail_id'] = session('pr_detail_id');

        DB::transaction(function () use ($input, $request) {
            $input =  DirectCostDetail::updateOrCreate(
                ['id' => $request->direct_cost_detail_id],
                $input
            );
        }); // end transcation


        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {
        $data = DirectCostDetail::find($id);
        return response()->json($data);
    }



    public function destroy($id)
    {

        DirectCostDetail::findOrFail($id)->delete();

        return response()->json(['status' => 'OK', 'message' => 'Data Successfully Deleted']);
    }
}
