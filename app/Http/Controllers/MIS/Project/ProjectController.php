<?php

namespace App\Http\Controllers\MIS\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrDocument;
use App\Models\Project\PrDetail;
use PhpParser\Node\Expr\Cast\Double;

class ProjectController extends Controller
{
    public function projectDocuments($projectId)
    {

        $prDocuments = PrDocument::where('pr_detail_id', $projectId)->get();
        return response()->json($prDocuments);
    }

    public function allProjectDocuments()
    {
        $prDocuments = PrDocument::all();
        return response()->json($prDocuments);
    }

    public function proejctSummaryMM($projectId)
    {
        $prDetail = PrDetail::find($projectId);
        $budgetUtilized = budgetUtilization($projectId);
        $remainingBudget = $budgetUtilized != '0.0' ? 100 - (float) rtrim($budgetUtilized, "%") : '0.0';
        $porjectSummary = [
            'total_cost' =>   $prDetail->prCost ? (int) $prDetail->prCost->total_cost ?? 0 - $prDetail->prCost->sales_tax ?? 0 : 0,
            'pending_invoices' => (int)pendingInvoicesAmount($projectId),
            'total_invoice' => (int)totalInvoicesAmount($projectId),
            'last_invoice' => lastInvoiceMonth($projectId),
            'budget_utilization' => doubleval($budgetUtilized),
            'remaining_budget' => (float)$remainingBudget,
            'current_progress' => currentProgress($projectId),
        ];
        return response()->json($porjectSummary);
    }
}
