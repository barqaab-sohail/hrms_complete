<?php

use App\Models\Project\Invoice\Invoice;
use App\Models\Project\PrRight;


function receivedInvoices($projectId){
	
	$receivedInvoices = Invoice::join('payment_receives','payment_receives.invoice_id','=','invoices.id')->where('invoices.pr_detail_id',$projectId)
           ->where('payment_status_id',2)
           ->select('invoices.*','payment_receives.amount','payment_receives.payment_date')
           ->get();
    return $receivedInvoices;
}

function pendingInvoices($projectId){
	
	$receivedInvoiceIds = receivedInvoices($projectId)->pluck('id')->toArray();
	
	$pendingInvoices = Invoice::whereNotIn('id',$receivedInvoiceIds)->where('pr_detail_id',$projectId)->get();

    return $pendingInvoices;
}

function isViewInvoice($projectId){
	$projectInvoiceRight = PrRight::where('hr_employee_id',Auth::user()->hrEmployee->id)->where('pr_detail_id',$projectId)->first();

		if(auth()->user()->can('view all invoices') ||
			auth()->user()->can('edit all invoices') ||
			auth()->user()->can('delete all invoices')){
			return true;
		}elseif($projectInvoiceRight){
			// 2 represent to view Invoice of specific project
			if ($projectInvoiceRight->invoice == 2){
				return true;
			}		
		}
	return false;
}

function isEditInvoice($projectId){
	$projectInvoiceRight = PrRight::where('hr_employee_id',Auth::user()->hrEmployee->id)->where('pr_detail_id',$projectId)->first();

		if(auth()->user()->can('edit all invoices')){
			return true;
		}elseif($projectInvoiceRight){
			// 3 represent to Edit Invoice of specific project
			if ($projectInvoiceRight->invoice == 3){
				return true;
			}		
		}

	return false;
}

function isDeleteInvoice($projectId){
	$projectInvoiceRight = PrRight::where('hr_employee_id',Auth::user()->hrEmployee->id)->where('pr_detail_id',$projectId)->first();

		if(auth()->user()->can('delete all invoices')){
			return true;
		}elseif($projectInvoiceRight){
			// 4 represent to delete Invoice of specific project
			if ($projectInvoiceRight->invoice == 4){
				return true;
			}		
		}

	return false;
}
