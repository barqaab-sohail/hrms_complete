<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset\AsMaintenance;
use App\Http\Requests\Asset\AsMaintenanceStore;
use DataTables;
use DB;

class AsMaintenanceController extends Controller
{
    public function index(){

       	$view =  view('asset.maintenance.create')->render();
        return response()->json($view);
    }

    public function create(Request $request){

    	if ($request->ajax()) {

            $data= AsMaintenance::where('asset_id',session('asset_id'))
                        ->latest()->get();
           

            return  DataTables::of($data)
                    ->addIndexColumn()  
                    ->addColumn('Edit', function($row){
                       
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editMaintenance">Edit</a>';
                                             
                            return $btn;
                    })
                    ->addColumn('Delete', function($row){                
                        
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteMaintenance">Delete</a>';
                           
                                 
                            return $btn;
                    })
                
                    ->addColumn('maintenance_detail', function($row){                
                        
                        $btn = $row->maintenance_detail;
                                                
                        return $btn;  
                           
                    })
                    ->addColumn('maintenance_cost', function($row){                
                        
                        $btn = number_format($row->maintenance_cost);
                                                
                        return $btn;  
                           
                    })
                    ->addColumn('maintenance_date', function($row){                
                       
                        $btn = $row->maintenance_date;
                        
                        return $btn;          
                    })
                    ->rawColumns(['Edit','Delete','maintenance_detail','maintenance_cost','maintenance_date'])
                    ->make(true);
          
        }
        
        $view =  view('asset.maintenance.create')->render();
        return response()->json($view);

    }

    public function store (AsMaintenanceStore $request){

        $input = $request->all();
        if($request->filled('maintenance_date')){
            $input ['maintenance_date']= \Carbon\Carbon::parse($request->maintenance_date)->format('Y-m-d');
        }

        if($request->filled('maintenance_cost')){
        	$input ['maintenance_cost'] = (int)str_replace(',', '', $input['maintenance_cost']);
        }

        DB::transaction(function () use ($input) {  
            
           
                AsMaintenance::updateOrCreate(['id' => $input['as_maintenance_id']],
                    
                    ['maintenance_detail'=> $input['maintenance_detail'],
                    'maintenance_cost'=> $input['maintenance_cost'],
                    'maintenance_date'=> $input['maintenance_date'],
                    'asset_id'=> session('asset_id')]); 

        }); // end transcation      
       return response()->json(['success'=>"Data saved successfully."]);

    }

    public function edit($id)
    {
        
        $asMaintenance = AsMaintenance::find($id);
    
        return response()->json($asMaintenance);
    }

    public function destroy ($id){

        DB::transaction(function () use ($id) {  
            AsMaintenance::find($id)->delete(); 
        }); // end transcation 

        return response()->json(['success'=>"data  delete successfully."]);

    }


}
