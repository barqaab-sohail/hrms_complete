<?php

namespace App\Http\Controllers\Project\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Requests\Project\Invoice\InvoiceStore;
use App\Models\Project\PrDetail;
use App\Models\Project\Invoice\Invoice;
use App\Models\Project\Invoice\InvoiceType;
use App\Models\Project\Invoice\InvoiceCost;
use App\Models\Project\Invoice\InvoiceStatus;
use DB;
use DataTables;


class InvoiceController extends Controller
{
    
    public function chart(){

    	$project = PrDetail::where('id','=',session('pr_detail_id'))->first();
    	//2039
    	if($project->id == 4){
    		return view ('project.charts.invoice.invoiceChart');
    	}


    }

    public function index() {
        $invoiceTypes = InvoiceType::all();
        $view =  view('project.invoice.create',compact('invoiceTypes'))->render();
        return response()->json($view);
    }

    public function create(Request $request){

        if ($request->ajax()) {
            $data = Invoice::where('pr_detail_id',session('pr_detail_id'))->latest()->get();

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
                    ->addColumn('invoice_type', function($row){                
                      
                           return $row->invoiceType->name??'';
                           
                    })
                    ->addColumn('cost', function($row){                
                      
                           return addComma($row->invoiceCost->cost??'');
                           
                    })
                    ->addColumn('sales_tax', function($row){                
                      
                           return addComma($row->invoiceCost->sales_tax??'');
                           
                    })
                     ->addColumn('total_value', function($row){                
                      		$salesTax= (int)$row->invoiceCost->sales_tax??'';
                      		$cost = (int)$row->invoiceCost->cost??'';
                      		$total = $salesTax + $cost;

                           return addComma($total);
                           
                    })
                 
                    ->rawColumns(['Edit','Delete','invoice_type_id','cost','sales_tax','total_value'])
                    ->make(true);
        }

        $prCostTypes = Invoice::all();
        
        $view =  view('project.invoice.create')->render();
        return response()->json($view);

	}


	public function store(InvoiceStore $request){

		$input = $request->all();
		
        $input['pr_detail_id']=session('pr_detail_id');
        $input ['cost']= intval(str_replace( ',', '', $request->cost));
        $input ['sales_tax']= intval(str_replace( ',', '', $request->sales_tax));
        if($request->filled('invoice_date')){
            $input ['invoice_date']= \Carbon\Carbon::parse($request->invoice_date)->format('Y-m-d');
            }
        

     	DB::transaction(function () use ($input, $request) {  

    		$invoice = Invoice::updateOrCreate(['id' => $input['invoice_id']],
                ['pr_detail_id'=> $input['pr_detail_id'],
                'invoice_type_id'=> $input['invoice_type_id'],
                'invoice_no'=> $input['invoice_no'],
                'invoice_date'=> $input['invoice_date'],
                'description'=> $input['description'],
                'reference'=> $input['reference']
            ]); 
 
            InvoiceCost::updateOrCreate(['invoice_id' => $invoice->id],
            ['invoice_id'=> $invoice->id,
            'cost'=> $input['cost'],
            'sales_tax'=> $input['sales_tax']
            ]);


            InvoiceStatus::updateOrCreate(['invoice_id' => $invoice->id],
            ['invoice_id'=> $invoice->id,
            'payment_status_id'=> 1,
            'date'=> $input['invoice_date']
            ]);

    	}); // end transcation

		return response()->json(['success'=>'Data saved successfully.']);
	}

	public function edit($id){

		$invoice= Invoice::find($id);
        $invoice = new Collection ($invoice);

        $invoiceCost = InvoiceCost::where('invoice_id',$id)->select(['cost','sales_tax'])->first();
        $invoiceCost = new Collection($invoiceCost);

     
       
        $invoice = $invoice->merge($invoiceCost);
        
        return response()->json($invoice);

	}






	public function destroy($id){

		Invoice::findOrFail($id)->delete();
		return response()->json(['status'=> 'OK', 'message' => "Data Successfully Deleted"]);
		
	}


}
