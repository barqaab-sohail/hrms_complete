<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\DirectCostDescription;
use App\Models\Project\Invoice\DirectCostDetail;
use App\Models\Project\PrDetail;
use Illuminate\Validation\Rule;
use DB;
use DataTables;

class DirectCostDetailController extends Controller
{
    public function show($prDetailId)
    {
        $directCostDescription = DirectCostDescription::all();
        $prDetail = PrDetail::find($prDetailId);
        $view =  view('project.direct_cost_detail.create', compact('directCostDescription', 'prDetail'))->render();
        return response()->json($view);
    }



    public function create(Request $request)
    {

        if ($request->ajax()) {

            $data =  DirectCostDetail::where('pr_detail_id', $request->prDetailId)->get();
            $sum =  DirectCostDetail::where('pr_detail_id', $request->prDetailId)->sum('amount');
            return DataTables::of($data)

                ->editColumn('direct_cost_description_id', function ($row) {

                    return $row->directCostDescription->name;
                })
                ->editColumn('amount', function ($row) {

                    return addComma($row->amount);
                })

                ->addColumn('edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editDetail">Edit</a>';
                    return $btn;
                })
                ->addColumn('delete', function ($row) {


                    $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteDetail">Delete</a>';

                    return $btn;
                })
                ->with(['sum' => $sum])
                ->rawColumns(['edit', 'delete'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'direct_cost_description_id' => ['required', Rule::unique('direct_cost_details')->where(fn($query) => $query->where('pr_detail_id', $request->prDetailId)->where('id', '!=', $request->direct_cost_detail_id))],
            'amount' => ['required']
        ]);

        $input = $request->all();
        $input['amount'] =  (int)str_replace(',', '', $request->amount);
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
