<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset\AsCondition;
use App\Models\Asset\AsConditionType;
use App\Http\Requests\Asset\AsConditionStore;
use DataTables;
use DB;

class AsConditionController extends Controller
{
    public function index(){
    	$asConditionTypes = AsConditionType::all();
       	$view =  view('asset.condition.create',compact('asConditionTypes'))->render();
        return response()->json($view);
    }

    public function create(Request $request){

    	if ($request->ajax()) {

            $data= AsCondition::where('asset_id',session('asset_id'))
                        ->latest()->get();
           

            return  DataTables::of($data)
                    ->addIndexColumn()  
                    ->addColumn('Edit', function($row){
                       
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editCondition">Edit</a>';
                                             
                            return $btn;
                    })
                    ->addColumn('Delete', function($row){                
                        
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteCondition">Delete</a>';
                           
                                 
                            return $btn;
                    })
                
                    ->addColumn('as_condition_type_id', function($row){                
                        
                        $btn = $row->asConditionType->name;
                                                
                        return $btn;  
                           
                    })
                    ->addColumn('condition_date', function($row){                
                       
                        $btn = $row->condition_date;
                        
                        return $btn;          
                    })
                    ->rawColumns(['Edit','Delete','as_condition_type_id','condition_date'])
                    ->make(true);
          
        }
        
        $view =  view('asset.condition.create')->render();
        return response()->json($view);

    }

    public function store (AsConditionStore $request){

        $input = $request->all();
        if($request->filled('condition_date')){
            $input ['condition_date']= \Carbon\Carbon::parse($request->maintenance_date)->format('Y-m-d');
        }

        DB::transaction(function () use ($input) {  
            
           
                AsCondition::updateOrCreate(['id' => $input['as_condition_id']],
                    
                    ['as_condition_type_id'=> $input['as_condition_type_id'],
                    'condition_date'=> $input['condition_date'],
                    'asset_id'=> session('asset_id')]); 

        }); // end transcation      
       return response()->json(['success'=>"Data saved successfully."]);

    }

    public function edit($id)
    {
        
        $asCondition = AsCondition::find($id);
    
        return response()->json($asCondition);
    }

    public function destroy ($id){

        DB::transaction(function () use ($id) {  
            AsCondition::find($id)->delete(); 
        }); // end transcation 

        return response()->json(['success'=>"data  delete successfully."]);

    }


}
