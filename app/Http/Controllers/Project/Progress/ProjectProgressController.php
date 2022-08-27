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
    private $test = 0;

    public function index()
    {

        $prProgressActivities = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->get();

        $view =  view('project.progress.achived.create', compact('prProgressActivities'))->render();
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

    public function create(Request $request)
    {
        // $projectLevel = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->max('level');
        // $progressActivities = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 1)->get();


        // if ($projectLevel > 1) {
        //     $levelOnes = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 1)->get();
        //     foreach ($levelOnes as $levelOne) {
        //         $Data1->push($levelOne);
        //         $levelTwoIds =  PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 2)->where('belong_to_activity', $levelOne->id)->pluck('id')->toArray();
        //         $levelOneSum = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->whereIn('belong_to_activity', $levelTwoIds)->sum('weightage');
        //         if ($levelOneSum === 0) {

        //             $levelTwoS = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('belong_to_activity', $levelOne->id)->sum('weightage');
        //             echo $levelOne->name . '--' . $levelTwoS . '<br>';
        //         } else {
        //             echo $levelOne->name . '- - ' . $levelOneSum . '<br>';
        //         }

        //         $levelTwos =  PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 2)->where('belong_to_activity', $levelOne->id)->get();
        //         foreach ($levelTwos as $levelTwo) {
        //             $levelTwoSum = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('belong_to_activity', $levelTwo->id)->sum('weightage');
        //             if ($levelTwoSum === 0) {
        //                 echo '-------' . $levelTwo->name . ' - ' . $levelTwo->weightage . '<br>';
        //             } else {
        //                 echo '-------' . $levelTwo->name . '- - ' . $levelTwoSum . '<br>';
        //             }

        //             $levelThrees =  PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 3)->where('belong_to_activity', $levelTwo->id)->get();
        //             foreach ($levelThrees as $levelThree) {
        //                 echo '----------------------' . $levelThree->name . '- - ' . $levelThree->weightage . '<br>';
        //             }
        //         }
        //     }
        // } else {
        //     foreach ($progressActivities as $progressActivity) {
        //         echo  $progressActivity->name . '- - ' . $progressActivity->weightage . '<br>';
        //     }
        // }


        if ($request->ajax()) {

            $customData = new Collection;

            $firstHeadings = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('weightage', 'NULL')->get();




            //Get Heading Detail
            if ($firstHeadings->count() > 0) {

                foreach ($firstHeadings as $firstHeading) {

                    $projectProgress = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('belong_to_activity', $firstHeading->id)->get();

                    $firstHeadingWeightageSum = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('belong_to_activity', $firstHeading->id)->sum('weightage');

                    //update Total firstHeading Weighatage
                    $firstHeading->weightage = $firstHeadingWeightageSum;


                    //Calculate Achived Progress 
                    //first get ids belong to firstHeadings
                    $firstHeadingIds = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('belong_to_activity', $firstHeading->id)->pluck('id')->toArray();

                    //variable create for total achived progress
                    $totalfirstHeadingProgress = 0.0;
                    //foreach loop for sum of total achived progress
                    foreach ($firstHeadingIds as $id) {
                        $firstHeadingProgress =  PrAchievedProgress::where('pr_progress_activity_id', $id)->latest()->first();
                        $totalfirstHeadingProgress += $firstHeadingProgress->percentage_complete ?? 0;
                    }

                    //create and add total firstHeading achived progress           
                    $firstHeading['progress_achived'] = $totalfirstHeadingProgress;
                    $firstHeading['firstHeading'] = 1;
                    //Add firstHeading detail into collection
                    $customData->push($firstHeading);

                    //Get sub activities
                    foreach ($projectProgress as $progress) {

                        //get sub activity achived progress
                        $latestProgress = PrAchievedProgress::where('pr_progress_activity_id', $progress->id)->latest()->first();
                        $lastUpdateProgress = '';
                        if ($latestProgress) {
                            $totalProgress = $latestProgress->percentage_complete;
                            $lastUpdateProgress = $latestProgress->date;
                        } else {
                            $totalProgress = 0.0;
                        }

                        //create and add sub activity achived progress
                        $progress['progress_achived'] = $totalProgress;
                        $progress['last_updated_progress'] = $lastUpdateProgress;

                        //add sub activities into collection
                        $customData->push($progress);
                    }
                } //end foreach
            } else { //if No Sub Items

                $projectProgress = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->get();

                foreach ($projectProgress as $progress) {
                    $latestProgress = PrAchievedProgress::where('pr_progress_activity_id', $progress->id)->latest()->first();
                    $lastUpdateProgress = '';
                    if ($latestProgress) {
                        $totalProgress = $latestProgress->percentage_complete;
                        $lastUpdateProgress = $latestProgress->date;
                    } else {
                        $totalProgress = 0.0;
                    }

                    //create and add sub activity achived progress
                    $progress['progress_achived'] = $totalProgress;
                    $progress['last_updated_progress'] = $lastUpdateProgress;
                    $progress['firstHeading'] = 0;
                    //add sub activities into collection
                    $customData->push($progress);
                }
            } //end if

            //Datatabe give collection for display
            $data = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->latest()->get();
            return DataTables::of($customData)
                ->editColumn('name', function ($row) {

                    if ($row->belong_to_activity === NULL) {
                        $btn = "<h3 style='color:red'>" . $row->name . "</h3>";
                    } else {
                        $btn = $row->name . '- ' . $row->belong_to_activity;
                    }
                    return $btn;
                })
                ->editColumn('last_updated_progress', function ($row) {
                    if ($row->belong_to_activity != NULL || $row->firstHeading == 0) {
                        $btn = $row->last_updated_progress;
                    } else {
                        $btn = '';
                    }
                    return $btn;
                })
                ->addColumn('Date', function ($row) {

                    if ($row->belong_to_activity != NULL || $row->firstHeading == 0) {
                        $btn = ' <input type="text" name="input_progress" id="date' . $row->id . '" class="form-control date_input" data-validation="required" readonly>';
                    } else {
                        $btn = '';
                    }
                    return $btn;
                })
                ->addColumn('Progress', function ($row) {

                    if ($row->belong_to_activity != NULL || $row->firstHeading == 0) {
                        $btn = '<input type="text" name="progress" id="progress' . $row->id . '" class="form-control notCapital progressInput" data-validation="required">';
                    } else {
                        $btn = '';
                    }

                    return $btn;
                })
                ->addColumn('Save', function ($row) {
                    if ($row->belong_to_activity != NULL || $row->firstHeading == 0) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Save" class="save btn btn-success btn-sm saveProgress">Save</a>';
                    } else {
                        $btn = '';
                    }

                    return $btn;
                })
                ->addColumn('Detail', function ($row) {
                    if ($row->belong_to_activity != NULL || $row->firstHeading == 0) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-primary btn-sm deleteModal">Detail</a>';
                    } else {
                        $btn = '';
                    }

                    return $btn;
                })

                ->rawColumns(['Date', 'Progress', 'Save', 'Detail', 'name', 'last_updated_progress'])
                ->make(true);
        }


        // $prProgressActivities = PrProgressActivity::where('pr_detail_id',session('pr_detail_id'))->get();
        // $view =  view('project.progress.achived.create',compact('prProgressActivities'))->render();
        // return response()->json($view);

    }

    public function store(AchievedProgressStore $request)
    {


        DB::transaction(function () use ($request) {

            PrAchievedProgress::create(
                [
                    'pr_detail_id' => session('pr_detail_id'),
                    'pr_progress_activity_id' => $request->activity_id,
                    'date' => $request->date,
                    'percentage_complete' => $request->progress
                ]
            );
        }); // end transcation

        return response()->json(['success' => "Data saved successfully"]);
    }


    //Delete Modal 
    public function edit(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = PrAchievedProgress::where('pr_detail_id', session('pr_detail_id'))->where('pr_progress_activity_id', $id)->latest()->get();

            return DataTables::of($data)
                ->addColumn('activity_name', function ($row) {

                    return $row->prProgressActivity->name ?? '';
                })
                ->addColumn('Delete', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="delete btn btn-danger btn-sm deleteProgress">Delete</a>';
                    return $btn;
                })

                ->rawColumns(['activity_name', 'Delete'])
                ->make(true);
        }
    }

    public function destroy($id)
    {

        PrAchievedProgress::findOrFail($id)->delete();
        return response()->json(['status' => 'OK', 'message' => "Data Successfully Deleted"]);
    }
}
