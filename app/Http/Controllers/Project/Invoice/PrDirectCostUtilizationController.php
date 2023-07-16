<?php

namespace App\Http\Controllers\Project\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\Invoice\DirectCostDetail;
use App\Models\Project\Invoice\Invoice;
use App\Models\Project\Invoice\PrDirectCostUtilization;
use App\Http\Requests\Project\Invoice\DirectCostUtilizationStore;


class PrDirectCostUtilizationController extends Controller
{
    public function index()
    {
        $directCostDetail = DirectCostDetail::where('pr_detail_id', session('pr_detail_id'))->get();
        // invoice_type_2 is Direct cost
        $invoices = Invoice::where('pr_detail_id', session('pr_detail_id'))->where('invoice_type_id', 2)->orderBy('invoice_no', 'desc')->get();
        $difference = $this->calculateDifferenceBetweenInvoiceUtilization($invoices);
        $view =  view('project.direct_cost_utilization.create', compact('directCostDetail', 'invoices', 'difference'))->render();
        return response()->json($view);
    }

    public function calculateDifferenceBetweenInvoiceUtilization($invoices)
    {
        $data = [];
        if ($invoices) {
            foreach ($invoices as $value) {
                $v['month'] = $value->invoiceMonth->invoice_month;
                $v['cost'] = $value->invoiceCost->amount;
                $totalUtilization = 0.0;
                $utilizations = $value->prDirectCostUtilization;
                foreach ($utilizations as $utilization) {
                    if ($v['month'] == $utilization->month_year) {
                        $totalUtilization += $utilization->amount;
                    }
                }
                $v['utilization'] = $totalUtilization;
                if ($v['utilization'] != $v['cost']) {
                    $v['difference'] = $v['utilization'] - $v['cost'];
                    array_push($data, $v);
                }
            }
        }
        return $data;
    }

    public function create()
    {
        $invoices = Invoice::where('pr_detail_id', session('pr_detail_id'))->where('invoice_type_id', 2)->orderBy('invoice_no', 'desc')->get();
        $difference = $this->calculateDifferenceBetweenInvoiceUtilization($invoices);
        $data =  PrDirectCostUtilization::where('pr_detail_id', session('pr_detail_id'))->get();
        return DataTables::of($data)
            ->editColumn('direct_cost_detail_id', function ($row) {

                return $row->directCostDescription->name;
            })
            ->editColumn('amount', function ($row) {

                return addComma($row->amount);
            })
            ->addColumn('edit', function ($row) {

                $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editUtilization">Edit</a>';
                return $btn;
            })
            ->addColumn('delete', function ($row) {


                $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteUtilization">Delete</a>';

                return $btn;
            })
            ->rawColumns(['edit', 'delete'])
            ->with('difference', $difference)
            ->make(true);
    }

    public function store(DirectCostUtilizationStore $request)
    {
        $input = $request->all();
        $input['pr_detail_id'] = session('pr_detail_id');

        DB::transaction(function () use ($input, $request) {
            $input =  PrDirectCostUtilization::updateOrCreate(
                ['id' => $request->utilization_id],
                $input
            );
        }); // end transcation


        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {
        $data = PrDirectCostUtilization::find($id);
        return response()->json($data);
    }



    public function destroy($id)
    {

        PrDirectCostUtilization::findOrFail($id)->delete();

        return response()->json(['status' => 'OK', 'message' => 'Data Successfully Deleted']);
    }
}
