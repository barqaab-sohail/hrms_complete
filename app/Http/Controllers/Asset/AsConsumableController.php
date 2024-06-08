<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset\AsConsumable;
use App\Models\Asset\Consumable;
use App\Http\Requests\Asset\AsConsumableStore;
use DataTables;
use DB;

class AsConsumableController extends Controller
{
    public function index(){
        $consumableItems = Consumable::all();
        $view =  view('asset.consumable.create',compact('consumableItems'))->render();
     return response()->json($view);
 }

 public function create(Request $request){

     if ($request->ajax()) {

         $data= AsConsumable::where('asset_id',session('asset_id'))
                     ->latest()->get();
        

         return  DataTables::of($data)
                 ->addIndexColumn()  
                 ->editColumn('consumable_id', function($row){                
                        
                    $btn = $row->consumable->name;
                                            
                    return $btn;  
                       
                })
                 ->addColumn('Edit', function($row){
                    
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editConsumable">Edit</a>';
                                          
                         return $btn;
                 })
                 ->addColumn('Delete', function($row){                
                     
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteConsumable">Delete</a>';
                        
                              
                         return $btn;
                 })
             
                 ->rawColumns(['Edit','Delete'])
                 ->make(true);
       
     }
     
     $view =  view('asset.consumable.create')->render();
     return response()->json($view);

 }

 public function store (AsConsumableStore $request){

     $input = $request->all();
     if($request->filled('consumable_date')){
         $input ['consumable_date']= \Carbon\Carbon::parse($request->consumable_date)->format('Y-m-d');
     }

     if($request->filled('consumable_cost')){
         $input ['consumable_cost'] = (int)str_replace(',', '', $input['consumable_cost']);
     }

     DB::transaction(function () use ($input) {  
         
        
             AsConsumable::updateOrCreate(['id' => $input['as_consumable_id']],
                 
                 ['consumable_id'=> $input['consumable_id'],
                 'consumable_cost'=> $input['consumable_cost'],
                 'consumable_date'=> $input['consumable_date'],
                 'asset_id'=> session('asset_id')]); 

     }); // end transcation      
    return response()->json(['success'=>"Data saved successfully."]);

 }

 public function edit($id)
 {
     
     $asConsumable = AsConsumable::find($id);
 
     return response()->json($asConsumable);
 }

 public function destroy ($id){

     DB::transaction(function () use ($id) {  
        AsConsumable::find($id)->delete(); 
     }); // end transcation 

     return response()->json(['success'=>"data  delete successfully."]);

 }
}
