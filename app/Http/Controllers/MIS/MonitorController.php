<?php

namespace App\Http\Controllers\MIS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\Invoice\Invoice;
use App\Models\Project\PrDetail;
use DataTables;

class MonitorController extends Controller
{
    public function index(Request $request)
    {


        if ($request->ajax()) {
            $totalPowerProjectsRunningIds = PrDetail::where('pr_division_id', 2)->where('pr_status_id', 1)->pluck('id')->toArray();
            $data = Invoice::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->groupBy('pr_detail_id')->orderBy('created_at', 'desc')->get();
            return DataTables::of($data)
                ->addColumn('project_name', function ($data) {
                    return $data->prDetail->project_no . '-' . $data->prDetail->name ?? '';
                })
                ->addColumn('invoice_month', function ($data) {
                    return $data->invoiceMonth->invoice_month ?? '';
                })
                ->editColumn('created_at', function ($data) {
                    return \Carbon\Carbon::parse($data->created_at)->format('d-M-Y');
                })
                ->rawColumns(['project_name'])
                ->make(true);
        }

        return view('MIS.monitor.list');
    }
}
