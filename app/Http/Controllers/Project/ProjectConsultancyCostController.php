<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\Project\Cost\PrCostType;
use App\Models\Project\Cost\PrCost;
use App\Models\Project\PrDetail;
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
            $data = PrCost::where('pr_detail_id',session('pr_detail_id'))->latest()->with('prCostType')->get();

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
                    ->editColumn('total_cost', function($row){                
                      
                           return addComma($row->total_cost??'');
                           
                    })

                    ->editColumn('mm_cost', function($row){                
                        if($row->mm_cost??''){
                           return addComma($row->mm_cost??'');
                        }else{
                            return '';
                        }
                           
                    })
                    ->editColumn('direct_cost', function($row){                

                           return addComma($row->direct_cost??'');
                           
                    })
                    ->editColumn('sales_tax', function($row){                
                      
                           return addComma($row->sales_tax??'');
                           
                    })
                    ->editColumn('contingency_cost', function($row){                
                      
                           return addComma($row->contingency_cost??'');
                           
                    })
    

                    ->rawColumns(['Edit','Delete','total_cost','pr_cost_type_id','mm_cost','direct_cost','sales_tax','congingency_cost'])
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
        
		
		if($request->filled('mm_cost')){
            $input ['mm_cost']= intval(str_replace( ',', '', $request->mm_cost));
        }

        if($request->filled('direct_cost')){
            $input ['direct_cost']= intval(str_replace( ',', '', $request->direct_cost));
        }

        if($request->filled('sales_tax')){
            $input ['sales_tax']= intval(str_replace( ',', '', $request->sales_tax));
        }

        if($request->filled('contingency_cost')){
            $input ['contingency_cost']= intval(str_replace( ',', '', $request->contingency_cost));
        }

     	DB::transaction(function () use ($input, $request) {  

    		$prConsultancyCost = PrCost::updateOrCreate(['id' => $input['cost_id']], $input); 


    	}); // end transcation

		return response()->json(['success'=>'Data saved successfully.']);
	}


	public function edit($id){
		$prCost = Prcost::find($id);
        return response()->json($consultancyCost);
	}

	


	public function destroy($id){

		PrCost::findOrFail($id)->delete();
		return response()->json(['status'=> 'OK', 'message' => "Data Successfully Deleted"]);
		
	}

	

}
