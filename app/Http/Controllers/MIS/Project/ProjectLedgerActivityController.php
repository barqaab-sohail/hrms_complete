<?php

namespace App\Http\Controllers\MIS\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrDetail;
use App\Models\Project\LedgerActivity;

class ProjectLedgerActivityController extends Controller
{
    public function misProjectLedgerActivity($projectId)
    {

        $ledgerActivity = LedgerActivity::where('pr_detail_id', $projectId)->get();
        $totalDebit = LedgerActivity::where('pr_detail_id', $projectId)->sum('debit');
        $totalCredit = LedgerActivity::where('pr_detail_id', $projectId)->sum('credit');
        $balance = $totalDebit - $totalCredit;
        $project = PrDetail::find($projectId);
        $projectCost = (int)$project->prCost->total_cost ?? '';


        return response()->json(['total_debit' => $totalDebit, 'total_credit' => $totalCredit, 'balance' => $balance, 'projectCost' => $projectCost]);
    }
}
