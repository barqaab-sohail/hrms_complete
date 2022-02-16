<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrDesignation;
use App\Models\Project\PrPosition;
use App\Models\Project\PrPositionType;
use App\Http\Requests\Project\PrPositionStore;
use DB;
use DataTables;

class ProjectPositionController extends Controller
{
    
    public function index() {
        
        $positionTypes = PrPositionType::all();
        $prPositions =  PrPosition::where('pr_detail_id', session('pr_detail_id'))->get();
        $hrDesignations = HrDesignation::all();
        //dd($managers->hodDesignation->name);
        $view =  view ('project.position.create', compact('positionTypes','hrDesignations'))->render();
        return response()->json($view);

    }



    public function create(Request $request){
        	
        if ($request->ajax()) {
            
            $data =  PrPosition::where('pr_detail_id', session('pr_detail_id'))->get();

            return DataTables::of($data)
                    
                    ->editColumn('hr_designation_id', function($row){
   
                        return $row->hrDesignation->name;
                    })
                    ->editColumn('pr_position_type_id', function($row){
   
                        return $row->prPositionType->name;
                    })

                    ->addColumn('edit', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editPosition">Edit</a>';
                            return $btn;
                    })
                    ->addColumn('delete', function($row){
   
                                                     
                           $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deletePosition">Delete</a>';
                            
                            return $btn;
                    })
                    ->rawColumns(['edit','delete'])
                    ->make(true);
                        
        }
    
    
    }

    public function store (PrPositionStore $request){

    	$input = $request->all();
    	$input['pr_detail_id'] = session('pr_detail_id');

    	DB::transaction(function () use ($input, $request) {  
             $input =  PrPosition::updateOrCreate(['id' => $request->position_id],
                  $input);

    	}); // end transcation


        return response()->json(['success'=>'Data saved successfully.']);

    }

     public function edit ($id){
    	$data = PrPosition::find($id);
    	return response()->json($data);
    }

   

    public function destroy($id){
    	
    	PrPosition::findOrFail($id)->delete(); 

    	return response()->json(['status'=> 'OK', 'message' => 'Data Successfully Deleted']);
    }

}
