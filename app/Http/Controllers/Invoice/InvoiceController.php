<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoice\InvoiceRight;
use App\Models\Invoice\InvoiceType;
use App\Models\Invoice\CostType;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceEscalation;
use App\Models\Invoice\InvoiceCost;
use App\Models\Invoice\InvoicePeriod;
use App\Models\Project\PrDetail;
use App\Http\Requests\Invoice\InvoiceStore;
use DB;

class InvoiceController extends Controller
{
    public function create(){

    	$prDetailIds = InvoiceRight::where('hr_employee_id',Auth::user()->hrEmployee->id)->get()->pluck('pr_detail_id')->toArray();

         $projects = PrDetail::wherein('id',$prDetailIds)->get();
         $invoiceTypes = InvoiceType::all();
         $costTypes = CostType::all();

    	return view ('invoice.create', compact('projects','invoiceTypes','costTypes'));
    }

    public function store(InvoiceStore $request){

    	$input = $request->all();
		if($request->filled('from')){
          $input ['from']= \Carbon\Carbon::parse($request->from)->format('Y-m-d');
        }
        if($request->filled('to')){
        $input ['to']= \Carbon\Carbon::parse($request->to)->format('Y-m-d');
        }

        if($request->filled('invoice_date')){
        $input ['invoice_date']= \Carbon\Carbon::parse($request->invoice_date)->format('Y-m-d');
        }

        DB::transaction(function () use ($input) {  

        	$invoice = Invoice::create($input);
        	$input['invoice_id']= $invoice->id;

        	InvoiceCost::create($input);

        	if($input['from']){
        		InvoicePeriod::create($input);
        	}

        	if($input['esc_cost']){
        		InvoiceEscalation::create($input);
        	}

        }); // end transcation


    	return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);

    }


}
