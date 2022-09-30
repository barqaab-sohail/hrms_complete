<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrDetail;
use App\Models\Project\Payment\PaymentReceive;
use  App\Models\Project\Invoice\Invoice;
use  App\Models\Project\Invoice\InvoiceCost;
use  App\Models\Project\PrMonthlyExpense;

class DashboardController extends Controller
{
    public function invoiceData()
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
    public function powerRunningProjectsTable()
    {

        $powerProjectsRunning = PrDetail::where('pr_division_id', 2)->where('pr_status_id', 1)->orderBy('contract_type_id', 'desc')->get();
        $projects = [];
        foreach ($powerProjectsRunning as $project) {
            $projects[] = [
                'id' => $project->id,
                'projectType' => $project->contract_type_id === 2 ? 'Man Month' : 'Lumpsum',
                'projectName' => $project->name,
                'paymentReceived' => addComma(PaymentReceive::where('pr_detail_id', $project->id)->sum('amount')),
                'pendingPayments' => addComma(pendingInvoicesAmount($project->id)),
                'budgetUtilization' => budgetUtilization($project->id),
                'projectProgress' => currentProgress($project->id),
                'latestInvoiceMonth' => $project->latestInvoiceMonth->invoice_month ?? '',
            ];
        }

        return response()->json($projects);
    }

    public function projectExpenseChart($projectId)
    {
        $month = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];

        $prDetail = PrDetail::find($projectId);
        $currentMonthInvoiceIds = Invoice::where('pr_detail_id', $projectId)->whereMonth('invoice_date',  \Carbon\Carbon::now()->month)->pluck('id')->toArray();
        $lastMonthInvoiceIds = Invoice::where('pr_detail_id', $projectId)->whereMonth('invoice_date',  \Carbon\Carbon::now()->subMonth(1)->month)->pluck('id')->toArray();
        $last2MonthInvoiceIds = Invoice::where('pr_detail_id', $projectId)->whereMonth('invoice_date',  \Carbon\Carbon::now()->subMonth(2)->month)->pluck('id')->toArray();

        $currentMonthInvoiceAmount = InvoiceCost::whereIn('invoice_id', $currentMonthInvoiceIds)->sum('amount');
        $lastMonthInvoiceAmount = InvoiceCost::whereIn('invoice_id', $lastMonthInvoiceIds)->sum('amount');
        $last2MonthInvoiceAmount = InvoiceCost::whereIn('invoice_id', $last2MonthInvoiceIds)->sum('amount');

        $currentMonthExpenses = PrMonthlyExpense::where('pr_detail_id', $projectId)->whereMonth('month', \Carbon\Carbon::now()->month)->sum('salary_expense') + PrMonthlyExpense::where('pr_detail_id', $projectId)->whereMonth('month', \Carbon\Carbon::now()->month)->sum('non_salary_expense');
        $lastMonthExpenses = PrMonthlyExpense::where('pr_detail_id', $projectId)->whereMonth('month', \Carbon\Carbon::now()->subMonth(1)->month)->sum('salary_expense') + PrMonthlyExpense::where('pr_detail_id', $projectId)->whereMonth('month', \Carbon\Carbon::now()->subMonth(1)->month)->sum('non_salary_expense');
        $last2MonthExpenses = PrMonthlyExpense::where('pr_detail_id', $projectId)->whereMonth('month', \Carbon\Carbon::now()->subMonth(2)->month)->sum('salary_expense') + PrMonthlyExpense::where('pr_detail_id', $projectId)->whereMonth('month', \Carbon\Carbon::now()->subMonth(2)->month)->sum('non_salary_expense');

        $currentMonth = $month[\Carbon\Carbon::now()->month - 1];
        $lastMonth = $month[\Carbon\Carbon::now()->subMonth(1)->month - 1];
        $last2Month = $month[\Carbon\Carbon::now()->subMonth(2)->month - 1];

        $currentMonthReceived = PaymentReceive::where('pr_detail_id', $projectId)->whereMonth('payment_date', \Carbon\Carbon::now()->month)->sum('amount');
        $lastMonthReceived = PaymentReceive::where('pr_detail_id', $projectId)->whereMonth('payment_date', \Carbon\Carbon::now()->subMonth(1)->month)->sum('amount');
        $last2MonthReceived = PaymentReceive::where('pr_detail_id', $projectId)->whereMonth('payment_date', \Carbon\Carbon::now()->subMonth(2)->month)->sum('amount');


        // $months = [$currentMonth, $lastMonth,  $last2Month];
        // $invoices = [$lastMonthInvoiceAmount, $last2MonthInvoiceAmount, $currentMonthInvoiceAmount];
        // $expenses = [$currentMonthExpenses, $lastMonthExpenses, $last2MonthExpenses];
        // $paymentReceived = [$currentMonthReceived, $lastMonthReceived, $last2MonthReceived];

        $data[] = [
            'months' => $currentMonth,
            'invoices' => $currentMonthInvoiceAmount,
            'expenses' => 40,
            'payments' => $currentMonthReceived
        ];

        $data[] = [
            'months' => $lastMonth,
            'invoices' => $lastMonthInvoiceAmount,
            'expenses' => 30,
            'payments' => $lastMonthReceived
        ];
        $data[] = [
            'months' => $last2Month,
            'invoices' => $last2MonthInvoiceAmount,
            'expenses' => 20,
            'payments' => $last2MonthReceived
        ];


        return  response()->json($data);
    }
    public function projectDetail($projectId)
    {
        $project = PrDetail::with('client', 'latestInvoiceMonth', 'invoiceCost', 'prCost')->find($projectId);
        $invoiceCostWOTaxWOExc = $project->invoiceCostWOEsc->sum('amount');
        $totalProjectCostWOTax = ($project->prCost->total_cost ?? 0) - ($project->prCost->sales_tax ?? 0);
        $percentageRemainingBudget = $totalProjectCostWOTax === 0 ? 'N/A' : round(($totalProjectCostWOTax - $invoiceCostWOTaxWOExc) / ($totalProjectCostWOTax) * 100, 2);
        $proejctDetail = [
            'projectName' => $project->name,
            'projectType' => $project->contract_type_id === 2 ? 'Man Month' : 'Lumpsum',
            'clientName' => $project->client->name,
            'commencementDate' => $project->commencement_date,
            'contractualCompletionDate' => $project->contractual_completion_date,
            'projectTotalCostWOTax' => addComma($totalProjectCostWOTax),
            'totalInvoicesAmountWOTaxWOExc' => addComma($invoiceCostWOTaxWOExc),
            'balaneBudget' => addComma($totalProjectCostWOTax - $invoiceCostWOTaxWOExc),
            'percentageRemainingBudget' => $percentageRemainingBudget,
            'lastInvoiceMonth' => $project->latestInvoiceMonth->invoice_month ?? '',

        ];

        return response()->json($proejctDetail);
    }
}
