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
        $porjectSummary = [
            'total_cost' =>   $prDetail->prCost->total_cost ?? '',
            'pending_invoices' => pendingInvoicesAmount($projectId),
            'total_invoice' => totalInvoicesAmount($projectId),
            'budget_tuilization' => budgetUtilization($projectId),
        ];
        return response()->json($porjectSummary);
    }
}
