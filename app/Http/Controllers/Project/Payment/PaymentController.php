<?php

namespace App\Http\Controllers\Project\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Requests\Project\Payment\PaymentStore;
use App\Models\Project\Invoice\Invoice;
use App\Models\Project\Payment\PaymentReceive;
use App\Models\Project\Payment\PaymentDeduction;
use App\Models\Project\Payment\PaymentStatus;
use DB;
use DataTables;

class PaymentController extends Controller
{
    
    public function getInvoiceValue($id)
    {
        
        $invoice = Invoice::find($id);
        $totalInvoiceValue = $invoice->invoiceCost->amount + $invoice->invoiceCost->sales_tax;
        
        $totalInvoiceValue = addComma($totalInvoiceValue);
        return response()->json($totalInvoiceValue);
    }

    public function index() {
       
       	// $invoices = Invoice::where('pr_detail_id',session('pr_detail_id'))->get();

        $invoices = pendingInvoices(session('pr_detail_id'));
        $paymentStatuses = PaymentStatus::all();
        $view =  view('project.payment.create',compact('invoices','paymentStatuses'))->render();
        return response()->json($view);
    }

    public function create(Request $request){

        if ($request->ajax()) {
            $data = PaymentReceive::where('pr_detail_id',session('pr_detail_id'))->latest()->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('Edit', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editPayment">Edit</a>';
                                                     
                            return $btn;
                    })
                    ->addColumn('Delete', function($row){                
                      
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletePayment">Delete</a>';
                            
                           
                            return $btn;
                    })
                    ->addColumn('invoice_no', function($row){                
                      
                           return $row->invoice->invoice_no??'';
                           
                    })
                    ->addColumn('total_deduction', function($row){                
                        $withholdingTax = (int)$row->deduction->withholding_tax??'';
                        $salesTax = (int)$row->deduction->sales_tax??'';
                        $others = (int)$row->deduction->others??'';
                        $totalDeduction = $withholdingTax + $salesTax + $others;
                           return addComma($totalDeduction);
                           
                    })
                    ->addColumn('amount', function($row){                
                      
                           return addComma($row->amount??'');
                           
                    })
                    ->addColumn('payment_status', function($row){                
                      
                           return $row->paymentStatus->name??'';
                           
                    })
                    
                 
                    ->rawColumns(['Edit','Delete','amount','invoice_no','total_deduction','payment_status'])
                    ->make(true);
        }

        $prCostTypes = Invoice::all();
        
        $view =  view('project.payment.create')->render();
        return response()->json($view);

	}

    public function store(PaymentStore $request){

        $input = $request->all();
        
        $input['pr_detail_id']=session('pr_detail_id');
        $input ['amount']= intval(str_replace( ',', '', $request->amount));
        $input ['withholding_tax']= intval(str_replace( ',', '', $request->withholding_tax));
        $input ['sales_tax']= intval(str_replace( ',', '', $request->sales_tax));
        $input ['other_deduction']= intval(str_replace( ',', '', $request->other_deduction));

        if($request->filled('payment_date')){
            $input ['payment_date']= \Carbon\Carbon::parse($request->payment_date)->format('Y-m-d');
        }
        if($request->filled('cheque_date')){
            $input ['cheque_date']= \Carbon\Carbon::parse($request->cheque_date)->format('Y-m-d');
        }
        

        DB::transaction(function () use ($input, $request) {  

            $paymentReceive = PaymentReceive::updateOrCreate(['id' => $input['payment_id']],
                ['pr_detail_id'=> $input['pr_detail_id'],
                'invoice_id'=> $input['invoice_id'],
                'amount'=> $input['amount'],
                'payment_date'=> $input['payment_date'],
                'cheque_no'=> $input['cheque_no'],
                'cheque_date'=> $input['cheque_date'],
                'payment_status_id'=> $input['payment_status_id']
                
            ]);

            PaymentDeduction::updateOrCreate(['payment_receive_id' =>  $paymentReceive->id],
                ['pr_detail_id'=> $input['pr_detail_id'],
                'withholding_tax'=> $input['withholding_tax'],
                'sales_tax'=> $input['sales_tax'],
                'others'=> $input['other_deduction'],
                'remarks'=> $input['remarks']
                
            ]);



        }); // end transcation

        return response()->json(['success'=>'Data saved successfully.']);
    }

    public function edit($id){

		$paymentReceive= PaymentReceive::find($id);
        $paymentReceive = new Collection ($paymentReceive);

        $paymentDeduction = PaymentDeduction::where('payment_receive_id',$id)->select(['withholding_tax','sales_tax','others'])->first();
        $paymentDeduction = new Collection($paymentDeduction);
        
        $paymentReceive = $paymentReceive->merge($paymentDeduction);
        return response()->json($paymentReceive);

	}



    public function destroy($id){

        PaymentReceive::findOrFail($id)->delete();
        return response()->json(['status'=> 'OK', 'message' => "Data Successfully Deleted"]);
        
    }

}
