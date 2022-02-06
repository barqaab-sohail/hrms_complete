<?php

namespace App\Http\Controllers\Input;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDesignation;
use App\Models\Input\InputProject;
use App\Models\Input\InputMonth;
use App\Models\Input\Input;
use App\Http\Requests\Input\InputStore;
use DataTables;
use DB;

class InputController extends Controller
{
    public function create(){

    	$inputMonths = InputMonth::where('is_lock',0)->get();
    	$hrEmployees = HrEmployee::where('hr_status_id',1)->get();
    	$hrDesignations = HrDesignation::all();
    	return view ('input.create',compact('inputMonths','hrEmployees','hrDesignations'));
    }


   public function projectList($id, $month, Request $request){

   	$inputProjects = InputProject::where('input_month_id',$month)->where('pr_detail_id',$id)->with('prDetail','hrEmployee','hrDesignation','monthlyInputEmployee')->first();
          
          if ($request->ajax()) {
              $data = InputProject::where('input_month_id',$month)->where('pr_detail_id',$id)->with('prDetail','hrEmployee','hrDesignation','input')->latest()->get();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('no', function($row){  
                        return '1';
                    })
                    ->addColumn('full_name', function($row){  
                        return $row->hrEmployee->first_name .' ' .$row->hrEmployee->last_name;
                    })
                    ->addColumn('designation', function($row){  
                        return $row->hrDesignation->name;
                    })
                    ->addColumn('input', function($row){  
                        return $row->input->input;
                    })
                    ->editColumn('remarks', function($row){  
                        return $row->input->remarks;
                    })
                    ->addColumn('edit', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editMonth">Edit</a>';
                            return $btn;
                    })
                    ->addColumn('delete', function($row){
   
                           $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteMonth">Delete</a>';
                        
                            return $btn;
                    })

                    ->rawColumns(['no','full_name','designation','input','remarks','edit','delete'])
                    ->make(true);            
          }
    	return response()->json($inputProjects);
   }

   public function store(InputStore $request){

   			$input = $request->all();
            
            DB::transaction(function () use ($input) {  
                Input::create($input);
            }); // end transcation
            
        return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);
            
   }

   public function edit($id){


   }

   public function destroy($id){
    Input::findOrFail($id)->delete();
    return response()->json('OK');
   }
}
