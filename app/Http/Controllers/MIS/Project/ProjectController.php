<?php

namespace App\Http\Controllers\MIS\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrDocument;
use App\Models\Project\PrDetail;

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
        $remainingBudget = $budgetUtilized != 0 ? 100 - (float) rtrim($budgetUtilized, "%") : 'N/A';
        $porjectSummary = [
            'total_cost' =>   $prDetail->prCost ? $prDetail->prCost->total_cost ?? '0' - $prDetail->prCost->sales_tax ?? '0' : 'N/A',
            'pending_invoices' => pendingInvoicesAmount($projectId),
            'total_invoice' => totalInvoicesAmount($projectId),
            'last_invoice' => lastInvoiceMonth($projectId),
            'budget_utilization' => $budgetUtilized,
            'remaining_budget' => $remainingBudget,
            'current_progress' => currentProgress($projectId),
        ];
        return response()->json($porjectSummary);
    }
}
