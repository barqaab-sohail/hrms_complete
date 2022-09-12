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
        $currentMonthReceived = addComma(PaymentReceive::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->whereMonth('payment_date', \Carbon\Carbon::now()->month)->sum('amount'));
        $lastMonthReceived = addComma(PaymentReceive::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->whereMonth('payment_date', \Carbon\Carbon::now()->subMonth()->month)->sum('amount'));

        $invoiceCurrentMonthIds = Invoice::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->whereMonth('invoice_date',  \Carbon\Carbon::now()->month)->pluck('id')->toArray();
        $InvoiceCurrentMonth = addComma(InvoiceCost::whereIn('invoice_id', $invoiceCurrentMonthIds)->sum('amount'));

        $invoiceLastMonthIds = Invoice::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->whereMonth('invoice_date',  \Carbon\Carbon::now()->subMonth()->month)->pluck('id')->toArray();
        $InvoiceLastMonth = addComma(InvoiceCost::whereIn('invoice_id', $invoiceLastMonthIds)->sum('amount'));





        $Received30Days = addComma(PaymentReceive::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->whereBetween('payment_date', [\Carbon\Carbon::now()->subDays(30)->startOfDay(), \Carbon\Carbon::now()->startOfDay()])->sum('amount'));
        $Received60Days = addComma(PaymentReceive::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->whereBetween('payment_date', [\Carbon\Carbon::now()->subDays(60)->startOfDay(), \Carbon\Carbon::now()->startOfDay()])->sum('amount'));

        $invoice30DaysIds = Invoice::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->whereBetween('invoice_date', [\Carbon\Carbon::now()->subDays(30)->startOfDay(), \Carbon\Carbon::now()->startOfDay()])->pluck('id')->toArray();
        $Invoice30Days = addComma(InvoiceCost::whereIn('invoice_id', $invoice30DaysIds)->sum('amount'));

        $invoice60DaysIds = Invoice::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->whereBetween('invoice_date', [\Carbon\Carbon::now()->subDays(60)->startOfDay(), \Carbon\Carbon::now()->startOfDay()])->pluck('id')->toArray();
        $Invoice60Days = addComma(InvoiceCost::whereIn('invoice_id', $invoice60DaysIds)->sum('amount'));

        $powerProjectsRunning = PrDetail::where('pr_division_id', 2)->where('pr_status_id', 1)->orderBy('contract_type_id', 'desc')->get();
        $projects = [];
        foreach ($powerProjectsRunning as $project) {
            $projects[] = [
                'projectType' => $project->contract_type_id === 2 ? 'Man Month' : 'Lumpsum',
                'projectName' => $project->name,
                'paymentReceived' => addComma(PaymentReceive::where('pr_detail_id', $project->id)->sum('amount')),
                'pendingPayment' => addComma(pendingInvoicesAmount($project->id)),
                'budgetUtilization' => budgetUtilization($project->id),
                'projectProgress' => currentProgress($project->id),

            ];
        }

        $porjectData = [
            'total_power_projects_running' => "$totalPowerProjectsRunning",
            'current_month_received' => $currentMonthReceived ? "$currentMonthReceived" : "0",
            'last_month_received' => $lastMonthReceived ? "$lastMonthReceived" : "0",
            'current_month_invoice' => $InvoiceCurrentMonth ? "$InvoiceCurrentMonth" : "0",
            'last_month_invoice' => $InvoiceLastMonth ? "$InvoiceLastMonth" : "0",
            'other_projects_running' => $otherProjectsRunning,
            'received_30_days' => $Received30Days,
            'invoice_30_days' => $Invoice30Days,
            'received_60_days' => $Received60Days,
            'invoice_60_days' => $Invoice60Days,
            'running_projects' => $projects,
        ];

        return response()->json($porjectData);
    }
    public function projectData()
    {

        $powerProjectsRunning = PrDetail::where('pr_division_id', 2)->where('pr_status_id', 1)->orderBy('contract_type_id', 'desc')->get();
        $projects = [];
        foreach ($powerProjectsRunning as $project) {
            $projects[] = [
                'projectType' => $project->contract_type_id === 2 ? 'Man Month' : 'Lumpsum',
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
