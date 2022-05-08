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


        //following code shift to create function

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

                //Get Heading Detail
                foreach($headings as $heading){
                	
                	$projectProgress = PrProgressActivity::where('pr_detail_id',session('pr_detail_id'))->where('belong_to_activity',$heading->id)->get();

                	$headingWeightageSum = PrProgressActivity::where('pr_detail_id',session('pr_detail_id'))->where('belong_to_activity',$heading->id)->sum('weightage');

                    //update Total Heading Weighatage
                	$heading->weightage=$headingWeightageSum;


                    //Calculate Achived Progress 
                    //first get ids belong to headings
                    $headingIds = PrProgressActivity::where('pr_detail_id',session('pr_detail_id'))->where('belong_to_activity',$heading->id)->pluck('id')->toArray();

                    //variable create for total achived progress
                    $totalHeadingProgress =0.0;
                    //foreach loop for sum of total achived progress
                    foreach($headingIds as $id){
                          $headingProgress =  PrAchievedProgress::where('pr_progress_activity_id',$id)->latest()->first();
                          $totalHeadingProgress += $headingProgress->percentage_complete??0;
                    }
                  
                    //create and add total heading achived progress           
                	$heading['progress_achived']=$totalHeadingProgress;

                    //Add heading detail into collection
                	$customData ->push($heading);
                	
                    //Get sub activities
                    foreach($projectProgress as $progress){
                    
                    //get sub activity achived progress
                    $latestProgress = PrAchievedProgress::where('pr_progress_activity_id', $progress->id)->latest()->first();
                   
                    if($latestProgress){
                      $totalProgress =$latestProgress->percentage_complete;
                    }else{
                         $totalProgress =0.0;
                    }

                    //create and add sub activity achived progress
                	$progress['progress_achived']=$totalProgress;

                    //add sub activities into collection
            		$customData ->push($progress);
            		}

                }

            //Datatabe give collection for display
            return DataTables::of($customData)
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
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Save" class="save btn btn-success btn-sm saveProgress">Save</a>';
                    }else{
                        $btn ='';
                    }
                                                 
                    return $btn;
                })
                ->addColumn('Delete', function($row){                
                    if($row->belong_to_activity!=NULL){
                       $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteModal">Delete</a>';
                       }else{
                        $btn ='';
                       }

                        return $btn;
                })

                ->rawColumns(['Date','Progress','Save','Delete'])
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


    //Delete Modal 
    public function edit(Request $request, $id){
        if ($request->ajax()) {
            $data = PrAchievedProgress::where('pr_detail_id',session('pr_detail_id'))->where('pr_progress_activity_id',$id)->latest()->get();

            return DataTables::of($data)
                    ->addColumn('activity_name', function($row){
                       
                       return $row->prProgressActivity->name??'';
                    })
                    ->addColumn('Delete', function($row){
                       
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="delete btn btn-danger btn-sm deleteProgress">Delete</a>';                           
                        return $btn;
                    })
                    
                    ->rawColumns(['activity_name','Delete'])
                    ->make(true);
        }
       

    }

    public function destroy($id){

        PrAchievedProgress::findOrFail($id)->delete();
        return response()->json(['status'=> 'OK', 'message' => "Data Successfully Deleted"]);
        
    }
}
