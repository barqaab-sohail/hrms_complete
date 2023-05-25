<?php

namespace App\Http\Controllers\MIS\Project;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Project\ProjectLedgerActivityController as Ledger;
use Illuminate\Http\Request;
use App\Models\Project\PrDetail;
use App\Models\Project\LedgerActivity;


class ProjectLedgerActivityController extends Controller
{
    public function misProjectLedgerActivity($projectId)
    {

        $checkUpdate = "NO";

        $lastUpdate = LedgerActivity::where('pr_detail_id', $projectId)->orderBy('updated_at', 'desc')->first();
        $difference = 11;
        if ($lastUpdate) {
            $lastUpdate =  \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $lastUpdate->updated_at);
            $today = \Carbon\Carbon::now();
            $difference = $today->diffInDays($lastUpdate);
        }
        if ($difference > 10) {
            $ledger = new Ledger();
            $ledger->importLedgerActivity($projectId);
            $checkUpdate = "Yes";
        }

        $totalDebit = LedgerActivity::where('pr_detail_id', $projectId)->sum('debit');
        $totalCredit = LedgerActivity::where('pr_detail_id', $projectId)->sum('credit');
        $balance = $totalDebit - $totalCredit;
        $project = PrDetail::find($projectId);
        $projectCost = (int)$project->prCost->total_cost ?? '';

        return response()->json(['total_debit' => $totalDebit, 'total_credit' => $totalCredit, 'balance' => $balance, 'projectCost' => $projectCost, 'update' => $checkUpdate]);
    }
}
