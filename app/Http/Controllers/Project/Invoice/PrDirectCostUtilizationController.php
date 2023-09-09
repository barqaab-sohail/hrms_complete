<?php

namespace App\Http\Controllers\Project\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\Invoice\DirectCostDetail;
use App\Models\Project\Invoice\Invoice;
use App\Models\Project\Invoice\PrDirectCostUtilization;
use App\Http\Requests\Project\Invoice\DirectCostUtilizationStore;
use DB;
use DataTables;


class PrDirectCostUtilizationController extends Controller
{
    public function index()
    {
        $directCostDetails = DirectCostDetail::where('pr_detail_id', session('pr_detail_id'))->get();
        // invoice_type_2 is Direct cost
        $invoices = Invoice::where('pr_detail_id', session('pr_detail_id'))->where('invoice_type_id', 2)->orderBy('invoice_no', 'desc')->get();
        $difference = $this->calculateDifferenceBetweenInvoiceUtilization($invoices);
        $view =  view('project.direct_cost_utilization.create', compact('directCostDetails', 'invoices', 'difference'))->render();
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
        $count = PrDirectCostUtilization::where('pr_detail_id', session('pr_detail_id'))->count();
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
            ->with('count', $count)
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

    public function exportViewDirectCost(Request $request)
    {

        $prDetailId = 51; //session('pr_detail_id');
        $months = PrDirectCostUtilization::where('pr_detail_id', $prDetailId)->select('month_year')->groupBy('month_year')->orderBy('month_year', 'asc')->pluck('month_year')->toArray();
        $directCostDetails = DirectCostDetail::where('pr_detail_id', $prDetailId)->get();

        $prMmUtilizations = PrDirectCostUtilization::where('pr_detail_id', $prDetailId)->select('month_year', 'amount', 'direct_cost_detail_id')->get();

        $directCostArray = [];
        foreach ($directCostDetails as $directCostDetail) {


            $directCostItemUtilizedTotal = PrDirectCostUtilization::where('pr_detail_id', $prDetailId)->where('direct_cost_detail_id', $directCostDetail->id)->sum('amount');
            $utilizations = PrDirectCostUtilization::where('pr_detail_id', $prDetailId)->where('direct_cost_detail_id', $directCostDetail->id)->get();

            $directCostValue['direct_cost_description'] = $directCostDetail->directCostDescription->name;
            $directCostValue['total_item_utilized'] =  $directCostItemUtilizedTotal;
            $directCostValue['total_budget'] =  $directCostDetail->amount;

            foreach ($months as $month) {
                $month = \Carbon\Carbon::parse($month)->format('Y-m-d');
                $utilization = PrDirectCostUtilization::where('pr_detail_id', $prDetailId)->where('direct_cost_detail_id', $directCostDetail->id)->where('month_year', $month)->first();
                $month = \Carbon\Carbon::parse($month)->format('F-Y');

                if ($utilization && $utilization->direct_cost_detail_id == $directCostDetail->id) {
                    $directCostValue[$month] = $utilization->amount;
                } else {
                    // if (!array_key_exists($month, $directCostValue)) {
                    $directCostValue[$month] = '';
                    //}
                }
            }
            array_push($directCostArray, $directCostValue);
        }
        // dd($directCostArray);
        // dd('ok');
        return view('project/direct_cost_utilization/report', compact('months', 'directCostArray'));
    }
}
