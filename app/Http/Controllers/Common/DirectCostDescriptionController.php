<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\DirectCostDescription;
use DB;
use DataTables;
use Illuminate\Validation\Rule;

class DirectCostDescriptionController extends Controller
{
    public function index(Request $request)
    {

        return view('common.direct_cost_description.list');
    }

    public function loadData(Request $request)
    {
        if ($request->ajax()) {
            $data = DirectCostDescription::orderBy('id', 'desc')->get();
            return DataTables::of($data)

                ->addColumn('edit', function ($data) {

                    $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Delete" class="btn btn-success btn-sm editDescription">Edit</a>';

                    return $button;
                })
                ->addColumn('delete', function ($data) {

                    $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteDescription">Delete</a>';
                    return $button;
                })
                ->rawColumns(['edit', 'delete'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => ['required', 'max:255',  Rule::unique('direct_cost_descriptions')->ignore($request->direct_cost_description_id)],
        ]);

        $input = $request->all();

        DB::transaction(function () use ($input) {

            DirectCostDescription::updateOrCreate(['id' => $input['direct_cost_description_id']], $input);
        }); // end transcation

        return response()->json(['status' => 'OK', 'message' => " Successfully Saved"]);
    }

    public function edit($id)
    {
        $data = DirectCostDescription::find($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            DirectCostDescription::findOrFail($id)->delete();
        }); // end transcation

        return response()->json(['success' => 'data  delete successfully.']);
    }
}
