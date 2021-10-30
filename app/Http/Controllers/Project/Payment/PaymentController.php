<?php

namespace App\Http\Controllers\Project\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\Project\Invoice\Invoice;
use App\Models\Project\Payment\PaymentReceive;
use DB;
use DataTables;

class PaymentController extends Controller
{
    
    public function index() {
       
       	$invoices = Invoice::where('pr_detail_id',session('pr_detail_id'))->get();
        $view =  view('project.payment.create',compact('invoices'))->render();
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
                      
                           return $row->invoiceNo->invoice_no??'';
                           
                    })
                    ->addColumn('total_duduction', function($row){                
                      
                           return '';
                           
                    })
                    
                 
                    ->rawColumns(['Edit','Delete','invoice_no','total_duduction'])
                    ->make(true);
        }

        $prCostTypes = Invoice::all();
        
        $view =  view('project.payment.create')->render();
        return response()->json($view);

	}


}
