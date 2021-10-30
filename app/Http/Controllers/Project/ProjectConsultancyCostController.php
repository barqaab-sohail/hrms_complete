<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\Project\PrCostType;
use App\Models\Project\PrDetail;
use App\Models\Project\PrConsultancyCost;
use App\Models\Project\PrManMonthCost;
use App\Models\Project\PrDirectCost;
use App\Models\Project\PrSalesTax;
use App\Models\Project\PrContingency;
use App\Http\Requests\Project\PrConsultancyCostStore;
use DB;
use DataTables;


class ProjectConsultancyCostController extends Controller
{
    
    public function index() {
        
        $prCostTypes = PrCostType::all();
        
        $view =  view('project.consultancyCost.create', compact('prCostTypes'))->render();
        return response()->json($view);

    }

	public function create(Request $request){

        if ($request->ajax()) {
            $data = PrConsultancyCost::where('pr_detail_id',session('pr_detail_id'))->latest()->with('prManMonthCost','prDirectCost','prSalesTax','prContingency','prCostType')->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('Edit', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editCost">Edit</a>';
                                                     
                            return $btn;
                    })
                    ->addColumn('Delete', function($row){                
                      
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteCost">Delete</a>';
                            
                           
                            return $btn;
                    })
                    ->addColumn('pr_cost_type_id', function($row){                
                      
                           return $row->prCostType->name??'';
                           
                    })
                    ->addColumn('total_cost', function($row){                
                      
                           return addComma($row->total_cost??'');
                           
                    })

                    ->addColumn('man_month_cost', function($row){                
                        if($row->prManMonthCost->man_month_cost??''){
                           return addComma($row->prManMonthCost->man_month_cost??'');
                        }
                           
                    })
                    ->addColumn('direct_cost', function($row){                

                           return addComma($row->prDirectCost->direct_cost??'');
                           
                    })
                    ->addColumn('sales_tax', function($row){                
                      
                           return addComma($row->prSalesTax->sales_tax??'');
                           
                    })
                    ->addColumn('contingency', function($row){                
                      
                           return addComma($row->prContingency->contingency??'');
                           
                    })
    

                    ->rawColumns(['Edit','Delete','total_cost','pr_cost_type_id','man_month_cost','direct_cost','sales_tax','congingency'])
                    ->make(true);
        }

        $prCostTypes = PrCostType::all();
        
        $view =  view('project.consultancyCost.create', compact('prCostTypes'))->render();
        return response()->json($view);

	}

	public function store(PrConsultancyCostStore $request){

		$input = $request->all();
		
        $input['pr_detail_id']=session('pr_detail_id');
        $input ['total_cost']= intval(str_replace( ',', '', $request->total_cost));
        
		
		if($request->filled('man_month_cost')){
            $input ['man_month_cost']= intval(str_replace( ',', '', $request->man_month_cost));
        }

        if($request->filled('direct_cost')){
            $input ['direct_cost']= intval(str_replace( ',', '', $request->direct_cost));
        }

        if($request->filled('sales_tax')){
            $input ['sales_tax']= intval(str_replace( ',', '', $request->sales_tax));
        }

        if($request->filled('contingency')){
            $input ['contingency']= intval(str_replace( ',', '', $request->contingency));
        }

     	DB::transaction(function () use ($input, $request) {  

    		$prConsultancyCost = PrConsultancyCost::updateOrCreate(['id' => $input['cost_id']],
                ['pr_detail_id'=> $input['pr_detail_id'],
                'pr_cost_type_id'=> $input['pr_cost_type_id'],
                'total_cost'=> $input['total_cost'],
                'remarks'=> $input['remarks']
            ]); 

            if($request->filled('man_month_cost')){
                PrManMonthCost::updateOrCreate(['pr_consultancy_cost_id' => $prConsultancyCost->id],
                ['pr_consultancy_cost_id'=> $prConsultancyCost->id,
                'man_month_cost'=> $input['man_month_cost']
                ]); 
            }else{
                PrManMonthCost::where('pr_consultancy_cost_id',$prConsultancyCost->id)->delete();
            }

            if($request->filled('direct_cost')){
                PrDirectCost::updateOrCreate(['pr_consultancy_cost_id' => $prConsultancyCost->id],
                ['pr_consultancy_cost_id'=> $prConsultancyCost->id,
                'direct_cost'=> $input['direct_cost']
                ]); 
            }else{
                PrDirectCost::where('pr_consultancy_cost_id',$prConsultancyCost->id)->delete();
            }

            if($request->filled('sales_tax')){
                PrSalesTax::updateOrCreate(['pr_consultancy_cost_id' => $prConsultancyCost->id],
                ['pr_consultancy_cost_id'=> $prConsultancyCost->id,
                'sales_tax'=> $input['sales_tax']
                ]); 
            }else{
                PrSalesTax::where('pr_consultancy_cost_id',$prConsultancyCost->id)->delete();
            }

            if($request->filled('contingency')){
                PrContingency::updateOrCreate(['pr_consultancy_cost_id' => $prConsultancyCost->id],
                ['pr_consultancy_cost_id'=> $prConsultancyCost->id,
                'contingency'=> $input['contingency']
                ]); 
            }else{
                PrContingency::where('pr_consultancy_cost_id',$prConsultancyCost->id)->delete();
            }

    	}); // end transcation

		return response()->json(['success'=>'Data saved successfully.']);
	}


	public function edit($id){

		
        $manMonthCost = PrManMonthCost::where('pr_consultancy_cost_id',$id)->select('man_month_cost')->first();
        $manMonthCost = new Collection($manMonthCost);

        $directCost = PrDirectCost::where('pr_consultancy_cost_id',$id)->select('direct_cost')->first();
        $directCost = new Collection($directCost);

        $salesTax = PrSalesTax::where('pr_consultancy_cost_id',$id)->select('sales_tax')->first();
        $salesTax = new Collection($salesTax);

        $contingency = PrContingency::where('pr_consultancy_cost_id',$id)->select('contingency')->first();
        $contingency = new Collection($contingency);

        
        $consultancyCost= PrConsultancyCost::find($id);
        $consultancyCost = new Collection ($consultancyCost);
        

        $consultancyCost = $consultancyCost->merge($manMonthCost);
        $consultancyCost = $consultancyCost->merge($directCost);
        $consultancyCost = $consultancyCost->merge($salesTax);
        $consultancyCost = $consultancyCost->merge($contingency);

        return response()->json($consultancyCost);

	}

	


	public function destroy($id){

		PrConsultancyCost::findOrFail($id)->delete();
		return response()->json(['status'=> 'OK', 'message' => "Data Successfully Deleted"]);
		
	}

	

}
