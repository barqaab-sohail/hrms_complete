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
        $updatedDate = LedgerActivity::where('pr_detail_id', $projectId)->orderBy('updated_at', 'desc')->first();
        $createdDate = LedgerActivity::where('pr_detail_id', $projectId)->orderBy('created_at', 'desc')->first();
        $lastUpdate = $createdDate;
        if ($updatedDate > $createdDate) {
            $lastUpdate = $updatedDate;
        }

        $difference = 29;
        if ($lastUpdate) {
            $lastUpdate =  \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $lastUpdate->updated_at)->format('d-m-Y');
            $today = \Carbon\Carbon::now();
            $difference = $today->diffInMinutes($lastUpdate);
        }

        $statusCode = 400;

        // if ($difference > 30 || !$lastUpdate) {
        //     $ledger = new Ledger();
        //     $ledgerResponse = $ledger->importLedgerActivity($projectId);
        //     $checkUpdate = "Yes";

        //     $statusCode = $ledgerResponse->status();
        // }

        $project = PrDetail::find($projectId);
        $projectCost = isset($project->prCost) ? (int)$project->prCost->total_cost : 0;

        if ($statusCode == 400) {
            $totalDebit = LedgerActivity::where('pr_detail_id', $projectId)->sum('debit');
            $totalCredit = LedgerActivity::where('pr_detail_id', $projectId)->sum('credit');
            $balance = $totalDebit - $totalCredit;
            return response()->json(['total_debit' => $totalDebit, 'total_credit' => $totalCredit, 'balance' => $balance, 'last_update' => $lastUpdate, 'projectCost' => $projectCost, 'update' => $checkUpdate]);
        } else {
            return response()->json(['total_debit' => 0, 'total_credit' => 0, 'balance' => 0, 'projectCost' => $projectCost, 'last_update' => $lastUpdate, 'update' => $checkUpdate]);
        }
    }

    public function updateProjectLedgerActivity($projectId)
    {
        $ledger = new Ledger();
        $ledgerResponse = $ledger->importLedgerActivity($projectId);
        $statusCode = $ledgerResponse->status();

        if ($statusCode == 400) {
            return $this->misProjectLedgerActivity($projectId);
        } else {
        }
    }
}
