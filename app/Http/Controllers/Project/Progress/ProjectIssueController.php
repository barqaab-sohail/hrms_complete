<?php

namespace App\Http\Controllers\Project\Progress;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\Progress\PrIssue;
use App\Http\Requests\Project\Progress\PrIssueStore;
use DB;
use DataTables;

class ProjectIssueController extends Controller
{
    
    public function index(){

    	$prIssues = PrIssue::where('pr_detail_id',session('pr_detail_id'))->get();
        $view =  view('project.progress.issues.create',compact('prIssues'))->render();
        return response()->json($view);
    }

      public function create(Request $request){

        if ($request->ajax()) {
            $data = PrIssue::where('pr_detail_id',session('pr_detail_id'))->latest()->get();

            return DataTables::of($data)
            		->editColumn('status',function ($row){
	                		$color = '';
			                if($row->status=="Pending"){
			                	$color = 'btn-danger';
			                }else{
			                	 $color='btn-success';
			                }
			            return '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn '.$color.'  btn-sm editStatus">'.$row->status.'</a>'; 

            		})
                    ->addColumn('edit', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editIssue">Edit</a>';
                                                     
                            return $btn;
                    })
                    ->addColumn('delete', function($row){                
                      
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteIssue">Delete</a>';
                                
                            return $btn;
                    })

                    ->rawColumns(['edit','delete','status'])
                    ->make(true);
        }
	}

	public function store(PrIssueStore $request){

        $input = $request->all();
        $input['pr_detail_id']=session('pr_detail_id');


        DB::transaction(function () use ($input, $request) {  

          PrIssue::updateOrCreate(['id' => $input['issue_id']], $input);

        }); // end transcation

        return response()->json(['success'=>'Data saved successfully.']);
    }

	public function edit($Id){
		$data = PrIssue::find($Id);
		return response()->json($data);
	}

	public function destroy($id){
		PrIssue::findOrFail($id)->delete();
        return response()->json(['status'=> 'OK', 'message' => "Data Successfully Deleted"]);
	}
}
