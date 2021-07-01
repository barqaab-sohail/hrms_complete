<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Office\Office;
use App\Models\Hr\HrEmployee;
use App\Models\Asset\AsLocation;
use App\Models\Asset\Asset;
use DataTables;
use DB;



class AsLocationController extends Controller
{
    
	public function index(){
    
    	$offices = Office::all();
    	$employees = HrEmployee::all();

       $view =  view('asset.location.create',compact('offices','employees'))->render();
        return response()->json($view);
    }

    public function create(Request $request){

    	if ($request->ajax()) {

    		$data = AsLocation::where('asset_id',session('asset_id'))->latest()->get();

            return  DataTables::of($data)
                    ->addIndexColumn()  
                    ->addColumn('Edit', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editLocation">Edit</a>';
                                                     
                            return $btn;
                    })
                    ->addColumn('Delete', function($row){                
                      
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteLocation">Delete</a>';
                                 
                            return $btn;
                    })
                
                     ->addColumn('location', function($row){                
                        
                        $btn = $row->asOffice->name;
                                              
                        return $btn;  
                           
                    })
                    ->addColumn('date', function($row){                
                        
                        $btn = $row->date;
                            
                        return $btn;          
                    })
                    ->rawColumns(['Edit','Delete','location','date'])
                    ->make(true);
          
        }
        
        $view =  view('asset.location.create')->render();
        return response()->json($view);

    }

    public function store (Request $request){

        $input = $request->all();
        if($request->filled('date')){
            $input ['date']= \Carbon\Carbon::parse($request->date)->format('Y-m-d');
        }

        DB::transaction(function () use ($input, $request) {  
            
            AsLocation::updateOrCreate(['id' => $input['as_location_id']],
                ['date'=> $input['date'],
                'office_id'=> $input['office_id'],
                'asset_id'=> session('asset_id')]); 


        }); // end transcation      
       return response()->json(['success'=>"Data saved successfully."]);

    }

    public function edit($id)
    {
        $asLocation = Asset::join('as_locations','assets.id','as_locations.asset_id')
                            //->join('as_allocations','assets.id','as_allocations.asset_id')
                            ->where('as_locations.id',$id)
                            //->where('as_allocations.id',$id)
                            ->select(['assets.description','as_locations.*'])
                            ->first();

       // AsLocation::find($id);
        return response()->json($asLocation);
    }







}
