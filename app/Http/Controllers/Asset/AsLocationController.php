<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Office\Office;
use App\Models\Hr\HrEmployee;
use App\Models\Asset\AsLocation;
use DataTables;
use DB;



class AsLocationController extends Controller
{
    
	public function index(){
    
    	$offices = Office::all();
    	$employees = HrEmployee::all();

    	// $data = Asset::join('as_locations','as_locations.asset_id','assets.id')
     //        		->select('assets.*','as_locations.office_id')
     //        		->latest()->get();
        // $data = Asset::where('id',session('asset_id'))->latest()->get();
        // dd($data->asLocation);

       $view =  view('asset.location.create',compact('offices','employees'))->render();
        return response()->json($view);
    }

    public function create(Request $request){


    	// $data = Asset::join('as_allocations','as_allocations.asset_id','assets.id')
     //        		->join('as_locations','as_locations.asset_id','assets.id')
     //        		->latest()->get();
        
    	if ($request->ajax()) {

         
    		$data = AsLocation::where('asset_id',session('asset_id'))->latest()->get();

            return  DataTables::of($data)
                    ->addIndexColumn()
                    
                    ->addColumn('Edit', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editDocument">Edit</a>';
                                                     
                            return $btn;
                    })
                    ->addColumn('Delete', function($row){                
                      
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteDocument">Delete</a>';
                            
                           
                            return $btn;
                    })
                
                     ->addColumn('location', function($row){                
                        
                        $btn = $row->office_id;
                                              
                        return $btn;  
                           
                    })
                    ->addColumn('date', function($row){                
                        
                        $btn = $row->date;
                            
                        return $btn;  
                           
                    })
                    ->rawColumns(['Edit','Delete','location','date'])
                    ->make(true);
          
        }
        
        $view =  view('asset.document.create')->render();
        return response()->json($view);

    } 






}
