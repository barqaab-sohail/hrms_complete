<?php

namespace App\Http\Controllers\Project\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrPosition;
use App\Models\Hr\HrEmployee;
use App\Http\Requests\Project\Invoice\MmUtilizationStore;
use App\Models\Project\Invoice\PrMmUtilization;
use App\Models\Project\Invoice\Invoice;
use DB;
use DataTables;

class MmUtilizationController extends Controller
{
    public function index()
    {
        $positions = PrPosition::where('pr_detail_id', session('pr_detail_id'))->get();
        $employees = HrEmployee::where('hr_status_id', 1)->get();
        $invoices = Invoice::where('pr_detail_id', session('pr_detail_id'))->get();
        $view =  view('project.mmUtilization.create', compact('positions', 'employees', 'invoices'))->render();
        return response()->json($view);
    }

    public function create()
    {
        $data =  PrMmUtilization::where('pr_detail_id', session('pr_detail_id'))->get();
        return DataTables::of($data)
            ->editColumn('hr_employee_id', function ($row) {

                return $row->hrEmployee->full_name;
            })
            ->editColumn('pr_position_id', function ($row) {

                return $row->hrDesignation->name ?? '';
            })
            ->editColumn('billing_rate', function ($row) {

                return addComma($row->billing_rate);
            })
            ->addColumn('total', function ($row) {
                return addComma($row->man_month * $row->billing_rate);
            })

            ->addColumn('edit', function ($row) {

                $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editMmUtilization">Edit</a>';
                return $btn;
            })
            ->addColumn('delete', function ($row) {


                $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteMmUtilization">Delete</a>';

                return $btn;
            })
            ->rawColumns(['edit', 'delete'])
            ->make(true);
    }

    public function store(MmUtilizationStore $request)
    {
        $input = $request->all();
        $input['pr_detail_id'] = session('pr_detail_id');
        //$input['month_year'] = \Carbon\Carbon::parse($request->month_year)->format('Y-m-d');

        DB::transaction(function () use ($input, $request) {
            $input =  PrMmUtilization::updateOrCreate(
                ['id' => $request->utilization_id],
                $input
            );
        }); // end transcation


        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {
        $data = PrMmUtilization::find($id);
        return response()->json($data);
    }



    public function destroy($id)
    {

        PrMmUtilization::findOrFail($id)->delete();

        return response()->json(['status' => 'OK', 'message' => 'Data Successfully Deleted']);
    }
}
