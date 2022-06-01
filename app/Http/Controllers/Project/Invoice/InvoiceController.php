<?php

namespace App\Http\Controllers\Project\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Project\Invoice\InvoiceStore;
use App\Models\Project\PrDetail;
use App\Models\Project\Invoice\Invoice;
use App\Models\Project\Invoice\InvoiceMonth;
use App\Models\Project\Invoice\InvoiceType;
use App\Models\Project\Invoice\InvoiceDocument;
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
                    ->addColumn('amount', function($row){                
                      
                           return addComma($row->invoiceCost->amount??'');
                           
                    })
                    ->addColumn('invoice_month', function($row){                
                      
                           return $row->invoiceMonth->invoice_month??'';
                           
                    })
                    ->addColumn('sales_tax', function($row){                
                      
                           return addComma($row->invoiceCost->sales_tax??'');
                           
                    })
                    ->addColumn('total_value', function($row){                
                      		$salesTax= (int)$row->invoiceCost->sales_tax??'';
                      		$amount = (int)$row->invoiceCost->amount??'';
                      		$total = $salesTax + $amount;

                           return addComma($total);
                           
                    })
                    ->addColumn('payment_status', function($row){                
                      
                           return $row->paymentStatus->name??'Pending';
                           
                    })
                    ->addColumn('invoice_document', function($row){                
                      
                        if($row->invoiceDocument)
                        {
                             $doc= "<img  id=\"ViewPDF$row->id\" src=". url('Massets/images/document.png')." href=".url('/storage/'.$row->invoiceDocument->path)." width=30/>";
                             return $doc;
                        }

                        return '';  
                           
                    })
                 
                    ->rawColumns(['Edit','Delete','invoice_type_id','amount','sales_tax','total_value','payment_status','invoice_document'])
                    ->make(true);
        }

        $prCostTypes = Invoice::all();
        
        $view =  view('project.invoice.create')->render();
        return response()->json($view);

	}


	public function store(InvoiceStore $request){

		$input = $request->all();
		
        $input['pr_detail_id']=session('pr_detail_id');
        $input ['amount']= intval(str_replace( ',', '', $request->amount));
        $input ['sales_tax']= intval(str_replace( ',', '', $request->sales_tax));
        if($request->filled('invoice_date')){
            $input ['invoice_date']= \Carbon\Carbon::parse($request->invoice_date)->format('Y-m-d');
            }
        if($request->filled('invoice_month')){
            $input ['invoice_month']= \Carbon\Carbon::parse($request->invoice_month)->format('Y-m-d');
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
            'amount'=> $input['amount'],
            'sales_tax'=> $input['sales_tax']
            ]);

            if ($request->filled('invoice_month')){
                InvoiceMonth::updateOrCreate(['invoice_id' => $invoice->id],
                    ['invoice_id'=> $invoice->id,
                    'invoice_month'=> $input['invoice_month']
                ]);
            }

            if ($request->hasFile('document')){
                $extension = request()->document->getClientOriginalExtension();
                $fileName = $input['invoice_no'].'-'. time().'.'.$extension;
                $folderName = "project/".session('pr_detail_id')."/invoice/";
                //store file
                $request->file('document')->storeAs('public/'.$folderName,$fileName);  
                $file_path = storage_path('app/public/'.$folderName.$fileName);

                InvoiceDocument::updateOrCreate(['invoice_id' => $invoice->id],
                ['invoice_id'=> $invoice->id,
                'pr_detail_id'=> $input['pr_detail_id'],
                'extension'=> $extension,
                'path'=> $folderName.$fileName,
                'size'=> $request->file('document')->getSize()
                ]);
            }

             

    	}); // end transcation


		return response()->json(['message'=>"Data saved successfully"]);
	}

	public function edit($id){

		$invoice= Invoice::find($id);
        $invoice = new Collection ($invoice);

        $invoiceCost = InvoiceCost::where('invoice_id',$id)->select(['amount','sales_tax'])->first();
        $invoiceCost = new Collection($invoiceCost);

        $invoiceDocument = InvoiceDocument::where('invoice_id',$id)->select('path')->first();
        $invoiceDocument = new Collection($invoiceDocument);

        $invoice = $invoice->merge($invoiceCost);
        $invoice = $invoice->merge($invoiceDocument);

        $invoiceMonth = InvoiceMonth::where('invoice_id',$id)->select('invoice_month')->first();
        if($invoiceMonth){
            $invoiceMonth = new Collection($invoiceMonth);
            $invoice = $invoice->merge($invoiceMonth);
        }
        
        return response()->json($invoice);

	}






	public function destroy($id){

        DB::transaction(function () use ($id) { 

            $invoiceDocument = InvoiceDocument::where('invoice_id',$id)->first();

            if($invoiceDocument){
                $path = public_path('storage/'.$invoiceDocument->path);

                if(File::exists($path)){
                        File::delete($path);
                }
            }

            Invoice::findOrFail($id)->delete();

        });  //end transaction


		return response()->json(['status'=> 'OK', 'message' => "Data Successfully Deleted"]);
		
	}


}
