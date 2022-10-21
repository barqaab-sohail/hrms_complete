<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrDetail;
use App\Models\Project\Payment\PaymentReceive;
use  App\Models\Project\Invoice\Invoice;
use  App\Models\Project\Invoice\InvoiceCost;
use App\Models\Project\Invoice\InvoiceMonth;
use  App\Models\Project\PrMonthlyExpense;

class DashboardController extends Controller
{
    public function invoiceData()
    {
        $totalNtdcProjectsRunning = PrDetail::where('client_id', 1)->where('pr_division_id', 2)->where('pr_status_id', 1)->count();
        $totalDiscoProjectsRunning = PrDetail::whereIn('client_id', [6, 7, 8, 9, 10, 11, 12, 13, 14, 15])->where('pr_division_id', 2)->where('pr_status_id', 1)->count();
        $otherProjectsRunning = PrDetail::where('pr_division_id', 2)->whereNotIn('client_id', [1, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15])->where('pr_status_id', 1)->count();

        $totalPowerProjectsRunning = PrDetail::where('pr_division_id', 2)->where('pr_status_id', 1)->count();

        //all power projects
        $totalPowerProjectsRunningIds = PrDetail::where('pr_division_id', 2)->pluck('id')->toArray();
        $currentMonthReceived = addComma(PaymentReceive::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->whereBetween('payment_date', [\Carbon\Carbon::now()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth()])->sum('amount'));
        $lastMonthReceived = addComma(PaymentReceive::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->WhereBetween('payment_date', [\Carbon\Carbon::now()->subMonth()->startOfMonth(), \Carbon\Carbon::now()->subMonth()->endOfMonth()])->sum('amount'));

        $invoiceCurrentMonthIds = Invoice::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->WhereBetween('invoice_date',   [\Carbon\Carbon::now()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth()])->pluck('id')->toArray();
        $InvoiceCurrentMonth = addComma(InvoiceCost::whereIn('invoice_id', $invoiceCurrentMonthIds)->sum('amount'));

        $invoiceLastMonthIds = Invoice::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->whereBetween('invoice_date',  [\Carbon\Carbon::now()->subMonth()->startOfMonth(), \Carbon\Carbon::now()->subMonth()->endOfMonth()])->pluck('id')->toArray();
        $InvoiceLastMonth = addComma(InvoiceCost::whereIn('invoice_id', $invoiceLastMonthIds)->sum('amount'));





        $Received30Days = addComma(PaymentReceive::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->whereBetween('payment_date', [\Carbon\Carbon::now()->subDays(30)->startOfDay(), \Carbon\Carbon::now()->startOfDay()])->sum('amount'));
        $Received60Days = addComma(PaymentReceive::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->whereBetween('payment_date', [\Carbon\Carbon::now()->subDays(60)->startOfDay(), \Carbon\Carbon::now()->startOfDay()])->sum('amount'));

        $invoice30DaysIds = Invoice::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->whereBetween('invoice_date', [\Carbon\Carbon::now()->subDays(30)->startOfDay(), \Carbon\Carbon::now()->startOfDay()])->pluck('id')->toArray();
        $Invoice30Days = addComma(InvoiceCost::whereIn('invoice_id', $invoice30DaysIds)->sum('amount'));

        $invoice60DaysIds = Invoice::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->whereBetween('invoice_date', [\Carbon\Carbon::now()->subDays(60)->startOfDay(), \Carbon\Carbon::now()->startOfDay()])->pluck('id')->toArray();
        $Invoice60Days = addComma(InvoiceCost::whereIn('invoice_id', $invoice60DaysIds)->sum('amount'));

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
        ];

        return response()->json($porjectData);
    }

    public function currentMonthPaymentReceived()
    {
        $totalPowerProjectsRunningIds = PrDetail::where('pr_division_id', 2)->pluck('id')->toArray();
        $currentMonthReceived = PaymentReceive::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->whereBetween('payment_date', [\Carbon\Carbon::now()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth()])->groupBy('pr_detail_id')->get();
        $payments = [];
        foreach ($currentMonthReceived as $payment) {
            $payments[] = [
                'projectId' => $payment->pr_detail_id ?? '',
                'projectName' => $payment->prDetail->name ?? '',
                'amountReceived' => addComma(PaymentReceive::where('pr_detail_id', $payment->pr_detail_id)->whereBetween('payment_date', [\Carbon\Carbon::now()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth()])->sum('amount')),
            ];
        }
        return response()->json($payments);
    }

    public function lastMonthPaymentReceived()
    {
        $totalPowerProjectsRunningIds = PrDetail::where('pr_division_id', 2)->pluck('id')->toArray();
        $lastMonthReceived = PaymentReceive::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->whereBetween('payment_date', [\Carbon\Carbon::now()->subMonth()->startOfMonth(), \Carbon\Carbon::now()->subMonth()->endOfMonth()])->groupBy('pr_detail_id')->get();
        $payments = [];
        foreach ($lastMonthReceived as $payment) {
            $payments[] = [
                'projectId' => $payment->pr_detail_id ?? '',
                'projectName' => $payment->prDetail->name ?? '',
                'amountReceived' => addComma(PaymentReceive::where('pr_detail_id', $payment->pr_detail_id)->whereBetween('payment_date', [\Carbon\Carbon::now()->subMonth()->startOfMonth(), \Carbon\Carbon::now()->subMonth()->endOfMonth()])->sum('amount')),
            ];
        }
        return response()->json($payments);
    }


    public function currentMonthInvoices()
    {
        $totalPowerProjectsRunningIds = PrDetail::where('pr_division_id', 2)->pluck('id')->toArray();
        $currentMonthInvoices = Invoice::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->WhereBetween('invoice_date',   [\Carbon\Carbon::now()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth()])->groupBy('pr_detail_id')->get();
        //$InvoiceCurrentMonth = addComma(InvoiceCost::whereIn('invoice_id', $invoiceCurrentMonthIds)->sum('amount'));
        $invoices = [];
        foreach ($currentMonthInvoices as $invoice) {
            $invoiceIds = Invoice::where('pr_detail_id', $invoice->pr_detail_id)->WhereBetween('invoice_date',   [\Carbon\Carbon::now()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth()])->pluck('id')->toArray();
            $invoices[] = [
                'projectName' => $invoice->prDetail->name ?? '',
                'invoiceAmount' => addComma(InvoiceCost::whereIn('invoice_id', $invoiceIds)->sum('amount')),
            ];
        }

        return response()->json($invoices);
    }

    public function lastMonthInvoices()
    {
        $totalPowerProjectsRunningIds = PrDetail::where('pr_division_id', 2)->pluck('id')->toArray();
        $lastMonthInvoices = Invoice::whereIn('pr_detail_id', $totalPowerProjectsRunningIds)->WhereBetween('invoice_date',   [\Carbon\Carbon::now()->subMonth()->startOfMonth(), \Carbon\Carbon::now()->subMonth()->endOfMonth()])->groupBy('pr_detail_id')->get();
        //$InvoiceCurrentMonth = addComma(InvoiceCost::whereIn('invoice_id', $invoiceCurrentMonthIds)->sum('amount'));
        $invoices = [];
        foreach ($lastMonthInvoices as $invoice) {
            $invoiceIds = Invoice::where('pr_detail_id', $invoice->pr_detail_id)->WhereBetween('invoice_date',   [\Carbon\Carbon::now()->subMonth()->startOfMonth(), \Carbon\Carbon::now()->subMonth()->endOfMonth()])->pluck('id')->toArray();
            $invoices[] = [
                'projectName' => $invoice->prDetail->name ?? '',
                'invoiceAmount' => addComma(InvoiceCost::whereIn('invoice_id', $invoiceIds)->sum('amount')),
            ];
        }

        return response()->json($invoices);
    }

    public function totalBudgetExpenditure($projectId)
    {
        $prDetail = PrDetail::find($projectId);
        $data = [];
        if ($prDetail->contract_type_id === 1) {
            $data['budget'] = $prDetail->prCost->total_cost ?? '';
            $salaryExpense = PrMonthlyExpense::where('pr_detail_id', $prDetail->id)->sum('salary_expense');
            $nonSalaryExpense = PrMonthlyExpense::where('pr_detail_id', $prDetail->id)->sum('non_salary_expense');
            $data['totalExpense'] = $salaryExpense + $nonSalaryExpense;
            $data['expenseUpdatedUptogit'] =  \Carbon\Carbon::createFromFormat('Y-m-d', PrMonthlyExpense::where('pr_detail_id', $prDetail->id)->max('month'))
                ->format('M-Y');
        }
        return response()->json($data);
    }

    public function powerRunningProjectsTable()
    {

        $powerProjectsRunning = PrDetail::with('latestInvoiceMonth')->where('pr_division_id', 2)->where('pr_status_id', 1)->orderBy('contract_type_id', 'desc')->get();
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
                'latestExpenditureMonth' => $project->latestExpenseMonth->month ?? '',
                'latestPaymentMonth' => $project->latestPaymentMonth->payment_date ?? '',
            ];
        }

        return response()->json($projects);
    }

    public function projectExpenseChart($projectId)
    {
        $month = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
        $currentMonth = $month[\Carbon\Carbon::now()->month - 1];
        $lastMonth = $month[\Carbon\Carbon::now()->subMonth(1)->month - 1];
        $last2Month = $month[\Carbon\Carbon::now()->subMonth(2)->month - 1];
        $last3Month = $month[\Carbon\Carbon::now()->subMonth(3)->month - 1];

        $currentMonthExpenses = PrMonthlyExpense::where('pr_detail_id', $projectId)->whereBetween('month', [\Carbon\Carbon::now()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth()])->sum('salary_expense') + PrMonthlyExpense::where('pr_detail_id', $projectId)->whereBetween('month', [\Carbon\Carbon::now()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth()])->sum('non_salary_expense');
        $lastMonthExpenses = PrMonthlyExpense::where('pr_detail_id', $projectId)->whereBetween('month', [\Carbon\Carbon::now()->subMonth()->startOfMonth(), \Carbon\Carbon::now()->subMonth()->endOfMonth()])->sum('salary_expense') + PrMonthlyExpense::where('pr_detail_id', $projectId)->whereBetween('month', [\Carbon\Carbon::now()->subMonth()->startOfMonth(), \Carbon\Carbon::now()->subMonth()->endOfMonth()])->sum('non_salary_expense');
        $last2MonthExpenses = PrMonthlyExpense::where('pr_detail_id', $projectId)->whereBetween('month', [\Carbon\Carbon::now()->subMonth(2)->startOfMonth(), \Carbon\Carbon::now()->subMonth(2)->endOfMonth()])->sum('salary_expense') + PrMonthlyExpense::where('pr_detail_id', $projectId)->whereBetween('month', [\Carbon\Carbon::now()->subMonth(2)->startOfMonth(), \Carbon\Carbon::now()->subMonth(2)->endOfMonth()])->sum('non_salary_expense');
        $last3MonthExpenses = PrMonthlyExpense::where('pr_detail_id', $projectId)->whereBetween('month', [\Carbon\Carbon::now()->subMonth(3)->startOfMonth(), \Carbon\Carbon::now()->subMonth(3)->endOfMonth()])->sum('salary_expense') + PrMonthlyExpense::where('pr_detail_id', $projectId)->whereBetween('month', [\Carbon\Carbon::now()->subMonth(3)->startOfMonth(), \Carbon\Carbon::now()->subMonth(3)->endOfMonth()])->sum('non_salary_expense');

        $currentMonthReceived = PaymentReceive::where('pr_detail_id', $projectId)->whereBetween('payment_date', [\Carbon\Carbon::now()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth()])->sum('amount');
        $lastMonthReceived = PaymentReceive::where('pr_detail_id', $projectId)->whereBetween('payment_date', [\Carbon\Carbon::now()->subMonth()->startOfMonth(), \Carbon\Carbon::now()->subMonth()->endOfMonth()])->sum('amount');
        $last2MonthReceived = PaymentReceive::where('pr_detail_id', $projectId)->whereBetween('payment_date', [\Carbon\Carbon::now()->subMonth(2)->startOfMonth(), \Carbon\Carbon::now()->subMonth(2)->endOfMonth()])->sum('amount');
        $last3MonthReceived = PaymentReceive::where('pr_detail_id', $projectId)->whereBetween('payment_date', [\Carbon\Carbon::now()->subMonth(3)->startOfMonth(), \Carbon\Carbon::now()->subMonth(3)->endOfMonth()])->sum('amount');

        $prDetail = PrDetail::find($projectId);
        if ($prDetail->contract_type_id === 2) {
            //Man Month Projects get invoice ids as per invoice month
            $invoiceIds = Invoice::where('pr_detail_id', $projectId)->pluck('id')->toArray();
            $currentMonthInvoiceIds = InvoiceMonth::whereIn('invoice_id', $invoiceIds)->whereBetween('invoice_month', [\Carbon\Carbon::now()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth()])->pluck('invoice_id')->toArray();
            $lastMonthInvoiceIds = InvoiceMonth::whereIn('invoice_id', $invoiceIds)->whereBetween('invoice_month',  [\Carbon\Carbon::now()->subMonth()->startOfMonth(), \Carbon\Carbon::now()->subMonth()->endOfMonth()])->pluck('invoice_id')->toArray();
            $last2MonthInvoiceIds = InvoiceMonth::whereIn('invoice_id', $invoiceIds)->whereBetween('invoice_month', [\Carbon\Carbon::now()->subMonth(2)->startOfMonth(), \Carbon\Carbon::now()->subMonth(2)->endOfMonth()])->pluck('invoice_id')->toArray();
            $last3MonthInvoiceIds = InvoiceMonth::whereIn('invoice_id', $invoiceIds)->whereBetween('invoice_month', [\Carbon\Carbon::now()->subMonth(3)->startOfMonth(), \Carbon\Carbon::now()->subMonth(3)->endOfMonth()])->pluck('invoice_id')->toArray();
        } else {
            //Other i.e lumpsum Projects get invoice ids as per invoice date
            $currentMonthInvoiceIds = Invoice::where('pr_detail_id', $projectId)->whereBetween('invoice_date', [\Carbon\Carbon::now()->startOfMonth(), \Carbon\Carbon::now()->endOfMonth()])->pluck('id')->toArray();
            $lastMonthInvoiceIds = Invoice::where('pr_detail_id', $projectId)->whereBetween('invoice_date', [\Carbon\Carbon::now()->subMonth()->startOfMonth(), \Carbon\Carbon::now()->subMonth()->endOfMonth()])->pluck('id')->toArray();
            $last2MonthInvoiceIds = Invoice::where('pr_detail_id', $projectId)->whereBetween('invoice_date',  [\Carbon\Carbon::now()->subMonth(2)->startOfMonth(), \Carbon\Carbon::now()->subMonth(2)->endOfMonth()])->pluck('id')->toArray();
            $last3MonthInvoiceIds = Invoice::where('pr_detail_id', $projectId)->whereBetween('invoice_date',  [\Carbon\Carbon::now()->subMonth(3)->startOfMonth(), \Carbon\Carbon::now()->subMonth(3)->endOfMonth()])->pluck('id')->toArray();
        }

        $currentMonthInvoiceAmount = InvoiceCost::whereIn('invoice_id', $currentMonthInvoiceIds)->sum('amount');
        $lastMonthInvoiceAmount = InvoiceCost::whereIn('invoice_id', $lastMonthInvoiceIds)->sum('amount');
        $last2MonthInvoiceAmount = InvoiceCost::whereIn('invoice_id', $last2MonthInvoiceIds)->sum('amount');
        $last3MonthInvoiceAmount = InvoiceCost::whereIn('invoice_id', $last3MonthInvoiceIds)->sum('amount');

        // $months = [$currentMonth, $lastMonth,  $last2Month];
        // $invoices = [$lastMonthInvoiceAmount, $last2MonthInvoiceAmount, $currentMonthInvoiceAmount];
        // $expenses = [$currentMonthExpenses, $lastMonthExpenses, $last2MonthExpenses];
        // $paymentReceived = [$currentMonthReceived, $lastMonthReceived, $last2MonthReceived];

        $data[] = [
            'months' => $currentMonth,
            'invoices' => $currentMonthInvoiceAmount,
            'expenses' => $currentMonthExpenses,
            'payments' => $currentMonthReceived
        ];

        $data[] = [
            'months' => $lastMonth,
            'invoices' => $lastMonthInvoiceAmount,
            'expenses' => $lastMonthExpenses,
            'payments' => $lastMonthReceived
        ];
        $data[] = [
            'months' => $last2Month,
            'invoices' => $last2MonthInvoiceAmount,
            'expenses' => $last2MonthExpenses,
            'payments' => $last2MonthReceived
        ];
        $data[] = [
            'months' => $last3Month,
            'invoices' => $last3MonthInvoiceAmount,
            'expenses' => $last3MonthExpenses,
            'payments' => $last3MonthReceived
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
