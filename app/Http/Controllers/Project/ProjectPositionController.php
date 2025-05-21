<?php

namespace App\Http\Controllers\Project;

use DB;
use DataTables;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDesignation;
use App\Models\Project\PrDetail;
use App\Models\Project\PrPosition;
use App\Http\Controllers\Controller;
use App\Models\Project\PrPositionType;
use App\Http\Requests\Project\PrPositionStore;

class ProjectPositionController extends Controller
{

    public function show($prDetailId)
    {

        $positionTypes = PrPositionType::all();
        $prPositions =  PrPosition::where('pr_detail_id', $prDetailId)->get();
        $hrDesignations = HrDesignation::all();
        $prDetail = PrDetail::find($prDetailId);
        $employees = HrEmployee::select(['id', 'first_name', 'last_name', 'employee_no'])->with('employeeDesignation')->get();
        $view =  view('project.position.create', compact('positionTypes', 'hrDesignations', 'employees', 'prDetail'))->render();
        return response()->json($view);
    }



    public function create(Request $request)
    {

        if ($request->ajax()) {

            $data =  PrPosition::where('pr_detail_id', $request->prDetailId)->get();

            return DataTables::of($data)

                ->editColumn('hr_designation_id', function ($row) {

                    return $row->hrDesignation->name;
                })
                ->editColumn('pr_position_type_id', function ($row) {

                    return $row->prPositionType->name;
                })
                ->editColumn('billing', function ($row) {

                    return addComma($row->billing);
                })
                ->editColumn('total_amount', function ($row) {

                    return addComma($row->total_amount);
                })

                ->addColumn('edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editPosition">Edit</a>';
                    return $btn;
                })
                ->addColumn('delete', function ($row) {


                    $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deletePosition">Delete</a>';

                    return $btn;
                })
                ->rawColumns(['edit', 'delete'])
                ->make(true);
        }
    }

    public function store(PrPositionStore $request)
    {

        $input = $request->all();

        DB::transaction(function () use ($input, $request) {
            $input =  PrPosition::updateOrCreate(
                ['id' => $request->position_id],
                $input
            );
        }); // end transcation


        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {
        $data = PrPosition::find($id);
        return response()->json($data);
    }



    public function destroy($id)
    {

        PrPosition::findOrFail($id)->delete();

        return response()->json(['status' => 'OK', 'message' => 'Data Successfully Deleted']);
    }
}
