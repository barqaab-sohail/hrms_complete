<?php

namespace App\Http\Controllers\Project\Progress;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\Progress\PrMonthlyProgress;
use App\Models\Project\Progress\PrProgressActivity;
use App\Http\Requests\Project\Progress\MonthlyProgressStore;
use DB;
use DataTables;

class MonthlyProgressController extends Controller
{
   
   public function index() {
       	
       	$progressActivities = PrProgressActivity::where('pr_detail_id',session('pr_detail_id'))->where('weightage',">",0)->get();

       	$prMonthlyProgress = PrMonthlyProgress::where('pr_detail_id',session('pr_detail_id'))->get();

        $view =  view('project.progress.monthly.create',compact('prMonthlyProgress','progressActivities'))->render();
        return response()->json($view);
    }

    public function create(Request $request){

        if ($request->ajax()) {
            $data = PrMonthlyProgress::where('pr_detail_id',session('pr_detail_id'))->latest()->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('Edit', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editMonthlyProgress">Edit</a>';
                                                     
                            return $btn;
                    })
                    ->addColumn('Delete', function($row){                
                      
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteMonthlyProgress">Delete</a>';
                            
                           
                            return $btn;
                    })
                    ->addColumn('pr_progress_activity_id', function($row){                
                      
                           return $row->prProgressActivity->name??'';
                           
                    })

                    ->addColumn('date', function($row){                
                      
                           return \Carbon\Carbon::parse($row->date??'')->format('M-Y');
                           
                    })
          
                 
                    ->rawColumns(['Edit','Delete','pr_progress_activity_id','date'])
                    ->make(true);
        }

               
        $view =  view('project.progress.monthly.create')->render();
        return response()->json($view);

	}

	public function store(MonthlyProgressStore $request){

        $input = $request->all();
        
        $input['pr_detail_id']=session('pr_detail_id');


        DB::transaction(function () use ($input, $request) {  

          PrMonthlyProgress::updateOrCreate(['id' => $input['monthlyProgress_id']],
                ['pr_detail_id'=> $input['pr_detail_id'],
                'pr_progress_activity_id'=> $input['pr_progress_activity_id'],
                'date'=> $input['date'],
                'scheduled'=> $input['scheduled'],
                'actual'=> $input['actual']
            ]);

        }); // end transcation

        return response()->json(['success'=>'Data saved successfully.']);
    }

    public function edit($id){

		$prMonthlyProgress= PrMonthlyProgress::find($id);
        return response()->json($prMonthlyProgress);

	}

	public function destroy($id){

        PrMonthlyProgress::findOrFail($id)->delete();
        return response()->json(['status'=> 'OK', 'message' => "Data Successfully Deleted"]);
        
    }
}
