<?php

namespace App\Http\Controllers\Project\Progress;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Requests\Project\Progress\AchievedProgressStore;
use App\Models\Project\Progress\PrProgressActivity;
use App\Models\Project\Progress\PrAchievedProgress;
use DB;
use DataTables;

class ProjectProgressController extends Controller
{
    public function index(){

    	$prProgressActivities = PrProgressActivity::where('pr_detail_id',session('pr_detail_id'))->get();

        $view =  view('project.progress.achived.create',compact('prProgressActivities'))->render();
        return response()->json($view);




    	// $projectProgress = PrProgressActivity::where('pr_detail_id',session('pr_detail_id'))->where('weightage','>',0)->get();
    	// $counts = PrProgressActivity::where('pr_detail_id',session('pr_detail_id'))->groupBy('belong_to_activity')
     //        ->get();
        
     //    $headings = PrProgressActivity::where('pr_detail_id',session('pr_detail_id'))->where('weightage','NULL')->get();
        
     //    echo 'Start heading list' . '<br>';
     //    foreach($headings as $heading){
        	
     //    	$projectProgress = PrProgressActivity::where('pr_detail_id',session('pr_detail_id'))->where('belong_to_activity',$heading->id)->get();

     //    	$sum = PrProgressActivity::where('pr_detail_id',session('pr_detail_id'))->where('belong_to_activity',$heading->id)->sum('weightage');
     //    	echo $heading->name ." - ".$sum.'<br>';
     //    	foreach($projectProgress as $progress){

    	// 	echo "    - ".$progress->name . "<br>";
    	// 	}


     //    }
     //    echo 'end heading list' . '<br>';
    	

    }

    public function create(Request $request){

        if ($request->ajax()) {
            $data = PrProgressActivity::where('pr_detail_id',session('pr_detail_id'))->latest()->get();

        $customData = new Collection;

    	$headings = PrProgressActivity::where('pr_detail_id',session('pr_detail_id'))->where('weightage','NULL')->get();

        foreach($headings as $heading){
        	
        	$projectProgress = PrProgressActivity::where('pr_detail_id',session('pr_detail_id'))->where('belong_to_activity',$heading->id)->get();

        	$sum = PrProgressActivity::where('pr_detail_id',session('pr_detail_id'))->where('belong_to_activity',$heading->id)->sum('weightage');
        	$heading->weightage=$sum;


            $headingIds = PrProgressActivity::where('pr_detail_id',session('pr_detail_id'))->where('belong_to_activity',$heading->id)->pluck('id')->toArray();

            $totalHeadingProgress =0.0;
            foreach($headingIds as $id){
                  $headingProgress =  PrAchievedProgress::where('pr_progress_activity_id',$id)->latest()->first();
                  $totalHeadingProgress += $headingProgress->percentage_complete??0;
            }
          
           
        	$heading['progress_achived']=$totalHeadingProgress;
        	$customData ->push($heading);
        	
            foreach($projectProgress as $progress){
            
            $latestProgress = PrAchievedProgress::where('pr_progress_activity_id', $progress->id)->latest()->first();
           
            if($latestProgress){
              $totalProgress =$latestProgress->percentage_complete;
            }else{
                 $totalProgress =0.0;
            }

            
        	$progress['progress_achived']=$totalProgress;
    		$customData ->push($progress);
    		}

        }


        

            return DataTables::of($customData)
                    ->addIndexColumn()
                    ->addColumn('Date', function($row){
                        
                        if($row->belong_to_activity!=NULL){
                        $btn = ' <input type="text" name="input_progress" id="date'.$row->id.'" class="form-control date_input" data-validation="required" readonly>';
                        }else{
                             $btn ='';
                        }

                                                     
                        return $btn;
                    })
                    ->addColumn('Progress', function($row){
                        
                        if($row->belong_to_activity!=NULL){
                        $btn = '<input type="text" name="progress" id="progress'.$row->id.'" class="form-control notCapital progressInput" data-validation="required">';
                        }else{
                            $btn ='';
                        }
                                                     
                        return $btn;
                    })
                    ->addColumn('Save', function($row){
                        if($row->belong_to_activity!=NULL){
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-success btn-sm saveProgress">Save</a>';
                        }else{
                            $btn ='';
                        }
                                                     
                        return $btn;
                    })
                    ->addColumn('Edit', function($row){                
                        if($row->belong_to_activity!=NULL){
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-primary btn-sm editProgress">Edit</a>';
                           }else{
                            $btn ='';
                           }
                            
                           
                            return $btn;
                    })
                    
                    
                    ->rawColumns(['Date','Progress','Save','Edit'])
                    ->make(true);
        }

      
        $prProgressActivities = PrProgressActivity::where('pr_detail_id',session('pr_detail_id'))->get();
        $view =  view('project.progress.achived.create',compact('prProgressActivities'))->render();
        return response()->json($view);

	}

	public function store(AchievedProgressStore $request){


        DB::transaction(function () use ($request) {  

            PrAchievedProgress::create(
                ['pr_detail_id'=> session('pr_detail_id'),
                'pr_progress_activity_id'=> $request->activity_id,
                'date'=>$request->date,
                'percentage_complete'=> $request->progress
            	]);

        }); // end transcation

        return response()->json(['success'=>"Data saved successfully"]);
    }

    public function edit(Request $request, $id){
        if ($request->ajax()) {
            $data = PrAchievedProgress::where('pr_detail_id',session('pr_detail_id'))->where('pr_progress_activity_id',$id)->latest()->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('activity_name', function($row){
                       
                       return "It is testing";
                    })
                    ->addColumn('Edit', function($row){
                       
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-success btn-sm editProgress">Edit</a>';                           
                        return $btn;
                    })
                    ->addColumn('Delete', function($row){
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-primary btn-sm deleteProgress">Edit</a>';
                            return $btn;
                    })
                    
                    
                    ->rawColumns(['activity_name','Edit','Delete'])
                    ->make(true);



        }
       

    }
}
