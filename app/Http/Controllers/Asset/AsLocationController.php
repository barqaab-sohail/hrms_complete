<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Office\Office;
use App\Models\Hr\HrEmployee;
use App\Models\Asset\AsLocation;
use App\Models\Asset\Asset;
use App\Http\Requests\Asset\AsLocationStore;
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

            $data= AsLocation::where('asset_id',session('asset_id'))
                        ->latest()->get();
           

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
                        
                        $btn = '';
                        if($row->office_id){
                            $btn = officeName($row->office_id);
                        }else{
                            $btn = employeeFullName($row->hr_employee_id);
                        }
                                              
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

    public function store (AsLocationStore $request){

         if($request->office_id && $request->hr_employee_id){

             return response()->json(['error'=>"Office or Employee, One value must empty."]);
        }

        $input = $request->all();
        if($request->filled('date')){
            $input ['date']= \Carbon\Carbon::parse($request->date)->format('Y-m-d');
        }

        if(!$request->filled('hr_employee_id')){
            $input ['hr_employee_id']= null;
        }
         if(!$request->filled('office_id')){
            $input ['office_id']= null;
        }

        DB::transaction(function () use ($input) {  
            
           
                AsLocation::updateOrCreate(['id' => $input['as_location_id']],
                    ['date'=> $input['date'],
                    'office_id'=> $input['office_id'],
                     'hr_employee_id'=> $input['hr_employee_id'],
                    'asset_id'=> session('asset_id')]); 

        }); // end transcation      
       return response()->json(['success'=>"Data saved successfully."]);

    }

    public function edit($id)
    {
        
        $asLocation = AsLocation::find($id);
    
        return response()->json($asLocation);
    }



   public function destroy ($id){

        DB::transaction(function () use ($id) {  

            AsLocation::find($id)->delete(); 
           
        }); // end transcation 

        return response()->json(['success'=>"data  delete successfully."]);

    }








}
