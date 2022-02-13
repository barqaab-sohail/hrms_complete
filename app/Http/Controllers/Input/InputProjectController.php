<?php

namespace App\Http\Controllers\Input;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Input\InputMonth;
use App\Models\Input\InputProject;
use App\Http\Requests\Input\InputProjectStore;
use App\Http\Requests\Input\CopyProjectStore;
use App\Models\Project\PrDetail;
use DataTables;
use DB;

class InputProjectController extends Controller
{
    

     public function create(Request $request)
    {
   
        $projects = PrDetail::all();
        $monthYears = InputMonth::where('is_lock',0)->get();
    
        if ($request->ajax()) {
            $data = InputProject::latest()->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('is_lock', function($row){  
                        if($row->is_lock==0){
                          return 'Open';
                        }else{
                           return 'Lock';
                        }     
                    })
                    ->addColumn('edit', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProject">Edit</a>';
                            return $btn;
                    })
                    ->addColumn('delete', function($row){
   
                          
                          
                           $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProject">Delete</a>';
                            
                            
                           
                            return $btn;
                    })
                     ->addColumn('month_year', function($row){

                            $monthYear = $row->inputMonth->month .' '. $row->inputMonth->year;
                            return   $monthYear;
                    })
                    ->editColumn('pr_detail_id', function($row){  
                        return $row->prDetail->name??''; 
                    })
                    ->rawColumns(['month_year','edit','delete'])
                    ->make(true);
                        
        }
      
       return view ('input.inputProject.create',compact('monthYears','projects'));
    }

    public function store(InputProjectStore $request){ 	
		   	
            DB::transaction(function () use ($request) {  
                InputProject::updateOrCreate(['id' => $request->input_project_id],
                ['input_month_id' => $request->input_month_id, 'pr_detail_id' => $request->pr_detail_id, 'is_lock' => $request->is_lock]);   
            }); // end transcation

            
            return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);
    }

    public function edit($id)
    {
        $book = InputProject::find($id);
        return response()->json($book);
    }

    public function show($id){
        $inputProjects = PrDetail::pluck("name","id");
        return response()->json($inputProjects);
    }

    public function destroy($id)
    {
        InputProject::find($id)->delete();
     
        return response()->json(['success'=>'Project deleted successfully.']);
    }

    public function copy(CopyProjectStore $request){    

        DB::transaction(function () use ($request) {  
               $inputProjects = InputProject::where('input_month_id',$request->copyFrom)->get();
               foreach($inputProjects as $inputProject){
                InputProject::create([
                    'input_month_id'=>$request->copyTo,
                    'pr_detail_id'=>$inputProject->pr_detail_id,
                    'is_lock'=>0
                    ]);
               }
               
        }); // end transcation

        return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);
    }
}
