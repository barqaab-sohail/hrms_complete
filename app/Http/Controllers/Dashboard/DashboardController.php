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
        $totalPowerProjectsRunning = PrDetail::where('pr_division_id', 2)->where('pr_status_id', 1)->count();
        $totalPowerProjectsRunningIds = PrDetail::where('pr_division_id', 2)->where('pr_status_id', 1)->pluck('id')->toArray();
        $totalNtdcProjectsRunning = PrDetail::where('client_id', 1)->where('pr_division_id', 2)->where('pr_status_id', 1)->count();
        $totalDiscoProjectsRunning = PrDetail::whereIn('client_id', [6, 7, 8, 9, 10, 11, 12, 13, 14, 15])->where('pr_division_id', 2)->where('pr_status_id', 1)->count();
        $otherProjectsRunning = PrDetail::where('pr_division_id', 2)->whereNotIn('client_id', [1, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15])->where('pr_status_id', 1)->count();
        $totalPowerPaymentReceived = addComma(PaymentReceive::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->sum('amount'));
        $totalPowerInvoicesId = Invoice::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->pluck('id')->toArray();
        $totalPowerInvoiceCost = addComma(InvoiceCost::whereIn('invoice_id', $totalPowerInvoicesId)->sum('amount'));

        $porjectData = [
            'total_power_projects_running' => $totalPowerProjectsRunning,
            'total_ntdc_projects_running' => $totalNtdcProjectsRunning,
            'total_disco_projects_running' => $totalDiscoProjectsRunning,
            'other_projects_running' => $otherProjectsRunning,
            'total_power_payment_received' => $totalPowerPaymentReceived,
            'total_Power_invoice_cost' => $totalPowerInvoiceCost
        ];

        $jsonobj = ["{'icon':'<HiOutlineRefresh/>','amount':'39,354','percentage':'-12%', 'title':'Refunds', 'iconColor':'rgb(0, 194, 146)','iconBg':'rgb(235, 250, 242)', 'pcColor': 'red-600'}"];

        return response()->json(['powerData' => $porjectData]);
    }
}
