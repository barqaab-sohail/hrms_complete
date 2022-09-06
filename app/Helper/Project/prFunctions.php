<?php

use App\Models\Project\PrDetail;
use App\Models\Project\Invoice\Invoice;
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
	$invoiceIds = Invoice::where('pr_detail_id', $projectId)->where('invoice_type_id', '!=', 3)->pluck('id')->toArray();
	$totalInvoices = InvoiceCost::whereIn('invoice_id', $invoiceIds)->sum('amount');
	if ($prDetail->prCost) {
		$totalCostWithoutGST = $prDetail->prCost->total_cost ?? '0' - $prDetail->prCost->sales_tax ?? '0';
	} else {
		return 0;
	}
	return round(($totalInvoices / $totalCostWithoutGST * 100), 2) . "%";
}
function currentProgress($projectId)
{
	$projectLevel = PrProgressActivity::where('pr_detail_id', $projectId)->max('level');
	if ($projectLevel) {
		if ($projectLevel > 1) {
			$levelOnes = PrProgressActivity::where('pr_detail_id', $projectId)->where('level', 1)->get();
			foreach ($levelOnes as $levelOne) {
			}
			return 'N/A';
		} else {
			$progressActivities = PrProgressActivity::where('pr_detail_id', $projectId)->where('level', 1)->get();
			$latestDate = PrProgressActivity::where('pr_detail_id', $projectId)->where('level', 1)->get();
			$totalProgress = 0.0;

			foreach ($progressActivities as $progressActivity) {

				//get activity achived progress
				$latestProgress = PrAchievedProgress::where('pr_progress_activity_id', $progressActivity->id)->latest()->first();
				if ($latestProgress) {
					$totalProgress += $latestProgress->percentage_complete ?? 0;
				}
			}
			return $totalProgress . '%';
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
