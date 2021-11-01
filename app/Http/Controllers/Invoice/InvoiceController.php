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
use DataTables;

class InvoiceController extends Controller
{
     public function index(){

        $prDetailIds = InvoiceRight::where('hr_employee_id',Auth::user()->hrEmployee->id)->get()->pluck('pr_detail_id')->toArray();

         $projects = PrDetail::wherein('id',$prDetailIds)->get();
         $invoiceTypes = InvoiceType::all();
         $costTypes = CostType::all();

        return view ('invoice.index', compact('projects','invoiceTypes','costTypes'));
    }

    public function show(Request $request, $id){
        if ($request->ajax()) {
            $data = Invoice::where('pr_detail_id',$id)->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('invoiceNo', function($row){                
                      
                           return $row->invoice_no;
                           
                })
                ->addColumn('status', function($row){                
                      
                           return $row->invoice_status_id;
                           
                })
                
                ->addColumn('Edit', function($row){                
                    
                       $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editInvoice">Edit</a>';
                        return $btn;
                    
                }) 
                ->addColumn('Delete', function($row){   

                    
                       $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteInvoice">Delete</a>'; 
                        return $btn;
                     
                })       
                ->rawColumns(['Edit','Delete'])
                ->make(true);
        }

    }


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

    public function edit($id){
        session()->put('edit_invoice', $id);
        $invoice = Invoice::with('invoiceCost','invoicePeriod')->find($id);
        return response()->json($invoice);
    }


    public function update(InvoiceStore $request, $id){

       if($id != session('edit_invoice')){
            return response()->json(['status'=> 'Not OK', 'message' => "Security Breach. No Data Change "]);
        }

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

        DB::transaction(function () use ($input, $id) {  



            Invoice::findOrFail($id)->update($input);
            //$input['invoice_id']= $id;
             InvoiceCost::where('invoice_id',$id)->first()->update($input);
        
            if($input['from']){
                InvoicePeriod::updateOrCreate(
                          ['invoice_id' => $id],
                          $input);
            }else{
                InvoicePeriod::where('invoice_id',$id)->delete();
            }

            // if($input['esc_cost']){
            //     InvoiceEscalation::create($input);
            // }

        }); // end transcation


        return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);

    }



    public function destroy ($id){

        DB::transaction(function () use ($id) {  
            Invoice::find($id)->delete();              
        }); // end transcation 
        return response()->json(['success'=>'data  delete successfully.']);

    }


}
