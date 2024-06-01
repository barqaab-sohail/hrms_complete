<?php

namespace App\Http\Controllers\MIS\Project;

use App\Http\Controllers\Controller;
use App\Models\Project\LedgerActivity;
use Illuminate\Http\Request;
use App\Models\Project\PrDocument;
use App\Models\Project\PrDetail;
use PhpParser\Node\Expr\Cast\Double;

class ProjectController extends Controller
{

    public function projects($status = null, $division = null)
    {
        if ($status == '0') {
            $status = null;
        }
        if ($status != null && $division != null) {
            $data = PrDetail::where('id', '!=', 1)->where('pr_status_id', $status)->where('pr_division_id', $division)->get();
        } elseif ($status != null) {
            $data = PrDetail::where('id', '!=', 1)->where('pr_status_id', $status)->get();
        } elseif ($division != null) {
            $data = PrDetail::where('id', '!=', 1)->where('pr_division_id', $division)->get();
        } else {
            $data = PrDetail::all();
        }

        foreach ($data as $project) {
            $projects[] =  array(
                'id' => $project->id,
                'name' => $project->name,
                "contract_type_id" => $project->contractType->name ?? '',
                "client" => $project->client->name ?? '',
                "commencement_date" => $project->commencement_date ?? '',
                "contractual_completion_date" => $project->contractual_completion_date ?? '',
                "actual_completion_date" => $project->actual_completion_date ?? '',
                "sub_projects" => $project->sub_project ?? '',
                "status" => $project->prStatus->name ?? '',
                "role" => $project->prRole->name ?? '',
                "division" => $project->prDivision->name ?? '',
                "share" => $project->share ?? '',
                "project_no" => $project->project_no ?? '',
            );
        }


        return response()->json($projects);
    }

    public function projectDetail($projectId)
    {
        $data = PrDetail::with('contractType','latestInvoiceMonth', 'prStatus', 'prRole', 'prDivision', 'client', 'ledgerActivity', 'prCost', 'latestExpenseMonth', 'expenses', 'prDocuments')->find($projectId);
        $totalCostWithouSalesTax = $data->prCost ? (int) $data->prCost->total_cost ?? 0 - $data->prCost->sales_tax ?? 0 : 0;
        $budgetUtilized = budgetUtilization($projectId);
        $remainingBudget = $budgetUtilized != '0.0' ? 100 - rtrim($budgetUtilized, "%") : '0.0';
        $expenses = $data->expenses;
        $ledgerActivity = $data->ledgerActivity;
        $totalSalaryExpenses = $expenses->sum('salary_expense');
        $totalReimbursableExpenses = $expenses->sum('non_salary_expense');
        $totalNonReimbursableSalaryExpenses = $expenses->sum('non_reimbursable_salary');
        $totalNonReimbursableExpenses = $expenses->sum('non_reimbursable_expense');
        $totalInvoices = $ledgerActivity->sum('debit');
        $totalInvoicesWithoutSalesTax = totalInvoicesWithoutSalesTax($projectId);
        $remainingAmount = $totalCostWithouSalesTax - $totalInvoicesWithoutSalesTax;
        $paymentReceived = $ledgerActivity->sum('credit');
        $lastPaymentReceived = $ledgerActivity->where('credit', '!=', 0)->first()->credit ?? '';
        $lastPaymentDate = $ledgerActivity->where('credit', '!=', 0)->first()->voucher_date ?? '';
        $lastInvoiceAmount =  $ledgerActivity->where('debit', '!=', 0)->first()->debit ?? '';
        $lastInvoiceDate =$data->latestInvoiceMonth?->invoice_month ??'';
        $currentProgressWithDate = currentProgress($projectId);
        $currentProgress = explode("-", $currentProgressWithDate)[3] ?? '';
        $progressDate =  mb_substr($currentProgressWithDate, 0, 10);
        $pendingPayment = $totalInvoices - $paymentReceived;
        $totalExpenses = $totalSalaryExpenses + $totalReimbursableExpenses + $totalNonReimbursableSalaryExpenses + $totalNonReimbursableExpenses;
        
        $project = [
            'name' => $data->name,
            'project_no' => $data->project_no ?? '',
            'commencement_date' => $data->commencement_date ? \Carbon\Carbon::parse($data->commencement_date)->format('M d, Y') : '',
            'contractualCompletionDate' => $data->contractual_completion_date ? \Carbon\Carbon::parse($data->contractual_completion_date)->format('M d, Y') : '',
            'share' => $data->share ?? '',
            'role' => $data->prRole->name ?? '',
            'client' => $data->client->name ?? '',
            'contract_type' => $data->contractType->name ?? '',
            'status' => $data->prStatus->name ?? '',
            'totalSalaryExpenses' => $totalSalaryExpenses ? number_format($totalSalaryExpenses) : '',
            'totalReimbursableExpenses' => $totalReimbursableExpenses ? number_format($totalReimbursableExpenses) : '',
            'totalNoReimbursableSalaryExpenses' => $totalNonReimbursableSalaryExpenses ? number_format($totalNonReimbursableSalaryExpenses) : '',
            'totalNonReimbursableExpenses' => $totalNonReimbursableExpenses ? number_format($totalNonReimbursableExpenses) : '',
            'totalExpenses' => $totalExpenses ? number_format($totalExpenses) : '',
            'expensesUpto' => $data->latestExpenseMonth->month ?? '',
            'totalInvoices' => $totalInvoices ? number_format($totalInvoices) : '',
            'totalInvoicesWithoutSalesTax'=>$totalInvoicesWithoutSalesTax?number_format($totalInvoicesWithoutSalesTax) : '',
            'paymentReceived' => $paymentReceived ? number_format($paymentReceived) : '',
            'pendingPayment' =>  $pendingPayment ? number_format($pendingPayment) : '',
            'lastPaymentReceived' => $lastPaymentReceived ? number_format($lastPaymentReceived) : '',
            'lastPaymentDate' => $lastPaymentDate,
            'lastInvoiceAmount' => $lastInvoiceAmount ? number_format($lastInvoiceAmount) : '',
            'lastInvoiceDate' => $lastInvoiceDate,
            'totalConsultancy' => $data->prCost?->total_cost ? number_format($data->prCost->total_cost) : '',
            'last_invoice' => lastInvoiceMonth($projectId),
            'remainingAmount'=>$remainingAmount ? number_format($remainingAmount) : '',
            'budget_utilization' =>  rtrim($budgetUtilized, "%"),
            'remaining_budget' => "$remainingBudget",
            'current_progress' => str_replace(' ', '', $currentProgress),
            'progress_date' => $progressDate,
            'documents' => $data->prDocuments,

        ];
        return response()->json($project);
    }

    public function projectDocuments($projectId)
    {

        $prDocuments = PrDocument::where('pr_detail_id', $projectId)->get();
        return response()->json($prDocuments);
    }

    public function allProjectDocuments()
    {
        $prDocuments = PrDocument::select('id', 'pr_folder_name_id', 'pr_detail_id', 'reference_no', 'description', 'document_date', 'file_name', 'extension', 'path', 'size')->get();
        return response()->json($prDocuments);
    }

    public function proejctSummaryMM($projectId)
    {
        $prDetail = PrDetail::find($projectId);
        $budgetUtilized = budgetUtilization($projectId);
        $remainingBudget = $budgetUtilized != '0.0' ? 100 - rtrim($budgetUtilized, "%") : '0.0';
        $porjectSummary = [
            'total_cost' =>   $prDetail->prCost ? (int) $prDetail->prCost->total_cost ?? 0 - $prDetail->prCost->sales_tax ?? 0 : 0,
            'pending_invoices' => (int)pendingInvoicesAmount($projectId),
            'total_invoice' => (int)totalInvoicesAmount($projectId),
            'last_invoice' => lastInvoiceMonth($projectId),
            'budget_utilization' =>  rtrim($budgetUtilized, "%"),
            'remaining_budget' => "$remainingBudget",
            'current_progress' => currentProgress($projectId),
            'status' => $prDetail->prStatus->name,
        ];
        return response()->json($porjectSummary);
    }

    public function manMonthProjectsStatus()
    {
        $projectsList = PrDetail::where('pr_status_id', 1)->where('contract_type_id', 2)->where('pr_division_id', 2)->get();
        $projects = [];
        foreach ($projectsList as $project) {
            $currProgressWithDate = currentProgress($project->id);
            $currentProgressDate = '';
            $currentProgress = '';
            if (strlen($currProgressWithDate) > 10) {
                $pieces = explode(" - ", $currProgressWithDate);
                $currentProgressDate = $pieces[0];
                $currentProgress = $pieces[1];
            }
            $budgetUtilized =  budgetUtilization($project->id);
            $color = '';
            if ((float) rtrim($budgetUtilized, "%") > (float) rtrim($currentProgress, "%")) {
                $color = 'red';
            } else {
                $color = 'green';
            }

            $projects[] = [
                'id' => $project->id,
                'projectType' => $project->contract_type_id === 2 ? 'Man Month' : 'Lumpsum',
                'projectName' => $project->project_no . " - " . $project->name,
                'lastInvoice' => lastInvoiceMonth($project->id),
                'budgetUtilization' => $budgetUtilized,
                'progress' =>   $currentProgress,
                'progressDate' => $currentProgressDate,
                'color' => $color,

            ];
        }
        return response()->json($projects);
    }
}
