<?php

use App\Models\Project\PrDetail;
use App\Models\Project\Invoice\Invoice;
use App\Models\Project\Invoice\InvoiceMonth;
use App\Models\Project\Invoice\InvoiceCost;
use App\Models\Project\PrRight;
use App\Models\Project\Progress\PrProgressActivity;
use App\Models\Project\Progress\PrAchievedProgress;


function receivedInvoices($projectId)
{

    $receivedInvoices = Invoice::join('payment_receives', 'payment_receives.invoice_id', '=', 'invoices.id')->where('invoices.pr_detail_id', $projectId)
        ->where('payment_status_id', 2)
        ->select('invoices.*', 'payment_receives.amount', 'payment_receives.payment_date')
        ->get();
    return $receivedInvoices;
}

function budgetUtilization($projectId)
{
    $prDetail = PrDetail::find($projectId);
    $totalBudget = $prDetail->prCost->total_cost ?? 0;
    if ($totalBudget) {
        $invoiceIds = Invoice::where('pr_detail_id', $projectId)->where('invoice_type_id', '!=', 3)->pluck('id')->toArray();
        $totalInvoices = InvoiceCost::whereIn('invoice_id', $invoiceIds)->sum('amount');
        $totalCostWithoutGST = $prDetail->prCost->total_cost ?? '0' - $prDetail->prCost->sales_tax ?? '0';
        return round(($totalInvoices / $totalCostWithoutGST * 100), 2) . "%";
    } else {
        return '0.0';
    }
}


function lastInvoiceMonth($projectId)
{
    $InvoiceIds = Invoice::where('pr_detail_id', $projectId)->pluck('id')->toArray();
    $lastInvoice = InvoiceMonth::whereIn('invoice_id', $InvoiceIds)->orderBy('invoice_month', 'desc')->first();
    return $lastInvoice->invoice_month ?? 'N/A';
}

function currentProgress($projectId)
{
    //return $pogress = PrAchievedProgress::where('pr_detail_id', $projectId)->orderBy('created_at', 'desc')->distinct('pr_progress_activity_id')->sum('percentage_complete');

    $totalAchievedProgress = 0.0;
    $SubProjectsAccomulativeProgress = 0.0;
    $lastAchievedProgressDate = '';
    $projectLevel = PrProgressActivity::where('pr_detail_id', $projectId)->max('level');
    if ($projectLevel) {
        if ($projectLevel > 1) {

            $levelOnes = PrProgressActivity::where('pr_detail_id', $projectId)->where('level', 1)->get();

            foreach ($levelOnes as $key => $levelOne) {
                $leveltwoSum = PrProgressActivity::where('pr_detail_id', $projectId)->where('level', 2)->where('belong_to_activity', $levelOne->id)->sum('weightage');
                $level2Ids = PrProgressActivity::where('pr_detail_id', $projectId)->where('level', 2)->where('belong_to_activity', $levelOne->id)->pluck('id')->toArray();
                $level3Ids = PrProgressActivity::where('pr_detail_id', $projectId)->where('level', 3)->whereIn('belong_to_activity', $level2Ids)->pluck('id')->toArray();
                //check if level two sum is 0 than it is heading
                if ($leveltwoSum === 0) {
                    foreach ($level3Ids as $level3Id) {
                        $totalCurrentProgress = PrAchievedProgress::where('pr_progress_activity_id', $level3Id)->latest()->first();
                        $totalAchievedProgress += $totalCurrentProgress->percentage_complete ?? 0;
                    }
                } else {
                    foreach ($level2Ids as $level2Id) {
                        $totalCurrentProgress = PrAchievedProgress::where('pr_progress_activity_id', $level2Id)->latest()->first();
                        $totalAchievedProgress += $totalCurrentProgress->percentage_complete ?? 0;
                    }
                }
                $subProjectWeightage = $levelOne->prSubTotalWeightage->total_weightage ?? 100;
                $SubProjectsAccomulativeProgress +=  $totalAchievedProgress * $subProjectWeightage / 100;
                $totalAchievedProgress = 0;
            }
            $latestDate = PrAchievedProgress::where('pr_detail_id', $projectId)->latest()->first();
            $lastAchievedProgressDate  = $latestDate->date ?? '';

            return $SubProjectsAccomulativeProgress . '% - ' . $lastAchievedProgressDate;
        } else {
            $progressActivities = PrProgressActivity::where('pr_detail_id', $projectId)->where('level', 1)->get();
            $latestDate = PrAchievedProgress::where('pr_detail_id', $projectId)->latest()->first();
            $lastAchievedProgressDate = $latestDate->date ?? '';
            foreach ($progressActivities as $progressActivity) {

                //get activity achived progress
                $latestProgress = PrAchievedProgress::where('pr_progress_activity_id', $progressActivity->id)->latest()->first();
                if ($latestProgress) {
                    $totalAchievedProgress += $latestProgress->percentage_complete ?? 0;
                }
            }
            return $totalAchievedProgress . '% - ' . $lastAchievedProgressDate;
        }
    } else {
        return 'N/A';
    }
}

function pendingInvoices($projectId)

{

    $receivedInvoiceIds = receivedInvoices($projectId)->pluck('id')->toArray();

    $pendingInvoices = Invoice::whereNotIn('id', $receivedInvoiceIds)->where('pr_detail_id', $projectId)->get();

    return $pendingInvoices;
}

function totalInvoicesAmount($projectId)
{

    $invoicesId = Invoice::where('pr_detail_id', $projectId)->pluck('id')->toArray();
    $totalInvoices = InvoiceCost::whereIn('invoice_id', $invoicesId)->sum('amount') + InvoiceCost::whereIn('invoice_id', $invoicesId)->sum('sales_tax');
    return $totalInvoices;
}

function pendingInvoicesAmount($projectId)
{

    $receivedInvoiceIds = receivedInvoices($projectId)->pluck('id')->toArray();
    $pendingInvoicesIds = Invoice::whereNotIn('id', $receivedInvoiceIds)->where('pr_detail_id', $projectId)->pluck('id')->toArray();
    $pendingAmount = InvoiceCost::whereIn('invoice_id', $pendingInvoicesIds)->sum('amount');
    return $pendingAmount;
}

function isViewInvoice($projectId)
{
    if (Auth::user()->hasRole('Super Admin')) {
        return true;
    }
    $projectInvoiceRight = PrRight::where('hr_employee_id', Auth::user()->hrEmployee->id)->where('pr_detail_id', $projectId)->first();

    if (
        auth()->user()->can('view all invoices') ||
        auth()->user()->can('edit all invoices') ||
        auth()->user()->can('delete all invoices')
    ) {
        return true;
    } elseif ($projectInvoiceRight) {
        // 2 represent to view Invoice of specific project
        if ($projectInvoiceRight->invoice == 2) {
            return true;
        }
    }
    return false;
}

function isEditInvoice($projectId)
{
    $projectInvoiceRight = PrRight::where('hr_employee_id', Auth::user()->hrEmployee->id)->where('pr_detail_id', $projectId)->first();

    if (auth()->user()->can('edit all invoices')) {
        return true;
    } elseif ($projectInvoiceRight) {
        // 3 represent to Edit Invoice of specific project
        if ($projectInvoiceRight->invoice == 3) {
            return true;
        }
    }

    return false;
}

function isDeleteInvoice($projectId)
{
    $projectInvoiceRight = PrRight::where('hr_employee_id', Auth::user()->hrEmployee->id)->where('pr_detail_id', $projectId)->first();

    if (auth()->user()->can('delete all invoices')) {
        return true;
    } elseif ($projectInvoiceRight) {
        // 4 represent to delete Invoice of specific project
        if ($projectInvoiceRight->invoice == 4) {
            return true;
        }
    }

    return false;
}

function projectStatus($id)
{
    if ($id === 1) {
        return 'In Progress';
    } else if ($id === 2) {
        return 'Completed';
    } else if ($id === 3) {
        return 'Suspended';
    } else {
        return '';
    }
}
