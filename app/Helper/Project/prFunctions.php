<?php

use App\Models\Project\Invoice\Invoice;


function receivedInvoices($projectId){
	
	$receivedInvoices = Invoice::join('payment_receives','payment_receives.invoice_id','=','invoices.id')->where('invoices.pr_detail_id',$projectId)
           ->where('payment_status_id',2)
           ->select('invoices.*','payment_receives.amount','payment_receives.payment_date')
           ->get();
    return $receivedInvoices;
}

function pendingInvoices($projectId){
	
	$receivedInvoiceIds = receivedInvoices($projectId)->pluck('id')->toArray();
	
	$pendingInvoices = Invoice::whereNotIn('id',$receivedInvoiceIds)->get();

    return $pendingInvoices;
}