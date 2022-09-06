<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrDetail;
use App\Models\Project\Payment\PaymentReceive;
use  App\Models\Project\Invoice\Invoice;
use  App\Models\Project\Invoice\InvoiceCost;

class DashboardController extends Controller
{
    public function powerData()
    {

        $totalNtdcProjectsRunning = PrDetail::where('client_id', 1)->where('pr_division_id', 2)->where('pr_status_id', 1)->count();
        $totalDiscoProjectsRunning = PrDetail::whereIn('client_id', [6, 7, 8, 9, 10, 11, 12, 13, 14, 15])->where('pr_division_id', 2)->where('pr_status_id', 1)->count();
        $otherProjectsRunning = PrDetail::where('pr_division_id', 2)->whereNotIn('client_id', [1, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15])->where('pr_status_id', 1)->count();

        $totalPowerProjectsRunning = PrDetail::where('pr_division_id', 2)->where('pr_status_id', 1)->count();

        $totalPowerProjectsRunningIds = PrDetail::where('pr_division_id', 2)->where('pr_status_id', 1)->pluck('id')->toArray();
        $Received90Days = addComma(PaymentReceive::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->whereBetween('payment_date', [\Carbon\Carbon::now()->subDays(90)->startOfDay(), \Carbon\Carbon::now()->startOfDay()])->sum('amount'));
        $Received60Days = addComma(PaymentReceive::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->whereBetween('payment_date', [\Carbon\Carbon::now()->subDays(60)->startOfDay(), \Carbon\Carbon::now()->startOfDay()])->sum('amount'));

        $invoice90DaysIds = Invoice::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->whereBetween('invoice_date', [\Carbon\Carbon::now()->subDays(90)->startOfDay(), \Carbon\Carbon::now()->startOfDay()])->pluck('id')->toArray();
        $Invoice90Days = addComma(InvoiceCost::whereIn('invoice_id', $invoice90DaysIds)->sum('amount'));

        $invoice60DaysIds = Invoice::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->whereBetween('invoice_date', [\Carbon\Carbon::now()->subDays(60)->startOfDay(), \Carbon\Carbon::now()->startOfDay()])->pluck('id')->toArray();
        $Invoice60Days = addComma(InvoiceCost::whereIn('invoice_id', $invoice60DaysIds)->sum('amount'));

        $porjectData = [
            'total_power_projects_running' => $totalPowerProjectsRunning,
            'other_projects_running' => $otherProjectsRunning,
            'received_90_days' => $Received90Days,
            'invoice_90_days' => $Invoice90Days,
            'received_60_days' => $Received60Days,
            'invoice_60_days' => $Invoice60Days,
        ];

        $jsonobj = ["{'icon':'<HiOutlineRefresh/>','amount':'39,354','percentage':'-12%', 'title':'Refunds', 'iconColor':'rgb(0, 194, 146)','iconBg':'rgb(235, 250, 242)', 'pcColor': 'red-600'}"];

        return response()->json($porjectData);
    }
    public function projectData()
    {

        $powerProjectsRunning = PrDetail::where('pr_division_id', 2)->where('pr_status_id', 1)->orderBy('contract_type_id', 'desc')->get();

        $projects = [];
        foreach ($powerProjectsRunning as $project) {
            $projects[] = [
                'projectName' => $project->name,
                'paymentReceived' => addComma(PaymentReceive::where('pr_detail_id', $project->id)->sum('amount')),
                'pendingPayment' => addComma(pendingInvoicesAmount($project->id)),
                'budgetUtilization' => budgetUtilization($project->id),
                'projectProgress' => currentProgress($project->id),



            ];
        }

        return response()->json($projects);
    }
}
