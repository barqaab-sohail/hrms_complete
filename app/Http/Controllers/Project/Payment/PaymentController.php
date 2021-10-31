<?php

namespace App\Http\Controllers\Project\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\Project\Invoice\Invoice;
use App\Models\Project\Payment\PaymentReceive;
use App\Models\Project\Payment\PaymentStatus;
use DB;
use DataTables;

class PaymentController extends Controller
{
    
    public function index() {
       
       	$invoices = Invoice::where('pr_detail_id',session('pr_detail_id'))->get();
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
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editInvoice">Edit</a>';
                                                     
                            return $btn;
                    })
                    ->addColumn('Delete', function($row){                
                      
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteInvoice">Delete</a>';
                            
                           
                            return $btn;
                    })
                    ->addColumn('invoice_no', function($row){                
                      
                           return $row->invoice->invoice_no??'';
                           
                    })
                    ->addColumn('total_deduction', function($row){                
                      
                           return '';
                           
                    })
                    
                 
                    ->rawColumns(['Edit','Delete','invoice_no','total_deduction'])
                    ->make(true);
        }

        $prCostTypes = Invoice::all();
        
        $view =  view('project.payment.create')->render();
        return response()->json($view);

	}

    public function store(Request $request){

        $input = $request->all();
        
        $input['pr_detail_id']=session('pr_detail_id');
        $input ['amount']= intval(str_replace( ',', '', $request->amount));
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

        }); // end transcation

        return response()->json(['success'=>'Data saved successfully.']);
    }

    public function destroy($id){

        PaymentReceive::findOrFail($id)->delete();
        return response()->json(['status'=> 'OK', 'message' => "Data Successfully Deleted"]);
        
    }

}
