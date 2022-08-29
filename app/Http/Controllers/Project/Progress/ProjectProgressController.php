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
    }

    public function create(Request $request)
    {
        $customData1 = new Collection;

        $projectLevel = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->max('level');

        if ($projectLevel > 1) {
            $levelOnes = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 1)->get();


            foreach ($levelOnes as $levelOne) {

                $leveltwoSum = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 2)->where('belong_to_activity', $levelOne->id)->sum('weightage');
                //check if level two sum is 0 than it is heading
                if ($leveltwoSum === 0) {

                    $level2Ids = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 2)->where('belong_to_activity', $levelOne->id)->pluck('id')->toArray();

                    //If level two sum is 0 than Total Weightage of Level is Sum of Level Two Weightage
                    $levelTwoTotalWeightage = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->whereIn('belong_to_activity', $level2Ids)->sum('weightage');
                    $level3Ids = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 3)->whereIn('belong_to_activity', $level2Ids)->pluck('id')->toArray();

                    //Sum of level 3 progress is also progress of Level 1 of Specific Level 2 
                    $totalAchievedProgressLevel1 = 0.0;
                    $lastAchievedProgressDateLevel1 = '';
                    foreach ($level3Ids as $level3Id) {
                        $totalCurrentProgress = PrAchievedProgress::where('pr_progress_activity_id', $level3Id)->latest()->first();
                        $totalAchievedProgressLevel1 += $totalCurrentProgress->percentage_complete ?? 0;
                    }

                    // Get last update date of level 3;
                    $lastUpdateProgress3 =  PrAchievedProgress::whereIn('pr_progress_activity_id', $level3Ids)->latest()->first();
                    $lastUpdateProgress3 =  $lastUpdateProgress3->date ?? '';

                    $customData1->push([
                        'id' => $levelOne->id,
                        'pr_detail_id' => $levelOne->pr_detail_id,
                        'level' => $levelOne->level,
                        'name' => $levelOne->name,
                        'weightage' => $levelTwoTotalWeightage,
                        'original_weightage' => $levelOne->weightage,
                        'belong_to_activity' => $levelOne->belong_to_activity,
                        'progress_achived' =>  $totalAchievedProgressLevel1,
                        'last_updated_progress' => $lastUpdateProgress3,
                    ]);
                } else {
                    //Sum of level 2 progress
                    $levelTwoIds =  PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('belong_to_activity', $levelOne->id)->pluck('id')->toArray();
                    $totalAchievedProgressLevel1 = 0.0;
                    $lastAchievedProgressDateLevel1 = '';
                    foreach ($levelTwoIds as $levelTwoId) {
                        $totalCurrentProgress = PrAchievedProgress::where('pr_progress_activity_id', $levelTwoId)->latest()->first();
                        $totalAchievedProgressLevel1 += $totalCurrentProgress->percentage_complete ?? 0;
                    }

                    // Get last update date of level 2;
                    $lastUpdateProgress2 =  PrAchievedProgress::whereIn('pr_progress_activity_id', $levelTwoIds)->latest()->first();
                    $lastUpdateProgress2 =  $lastUpdateProgress2->date ?? '';

                    $customData1->push([
                        'id' => $levelOne->id,
                        'pr_detail_id' => $levelOne->pr_detail_id,
                        'level' => $levelOne->level,
                        'name' => $levelOne->name,
                        'weightage' => $leveltwoSum,
                        'original_weightage' => $levelOne->weightage,
                        'belong_to_activity' => $levelOne->belong_to_activity,
                        'progress_achived' => $totalAchievedProgressLevel1,
                        'last_updated_progress' => $lastUpdateProgress2,
                    ]);
                }
                //Level Two Working
                $levelTwos =  PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 2)->where('belong_to_activity', $levelOne->id)->get();
                foreach ($levelTwos as $levelTwo) {
                    $levelTwoSum = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('belong_to_activity', $levelTwo->id)->sum('weightage');
                    //get Level 2 sub activity achived progress
                    $latestProgress2 = PrAchievedProgress::where('pr_progress_activity_id', $levelTwo->id)->latest()->first();
                    $lastUpdateProgress2 = '';
                    //check if Level 2 have last level
                    $totalAchievedProgressLevel2 = 0.0;
                    if ($latestProgress2) {
                        $totalAchievedProgressLevel2 = $latestProgress2->percentage_complete;
                        $lastUpdateProgress2 = $latestProgress2->date;
                    } else {
                        //Otherwise get level 3 all ids
                        $levelThreeIds =  PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('belong_to_activity', $levelTwo->id)->pluck('id')->toArray();
                        // Get last update date of level 2;
                        $lastUpdateProgress2 =  PrAchievedProgress::whereIn('pr_progress_activity_id', $levelThreeIds)->latest()->first();
                        $lastUpdateProgress2 =  $lastUpdateProgress2->date ?? '';

                        //Sum of level 2 progress
                        foreach ($levelThreeIds as $levelThreeId) {
                            $totalCurrentProgress = PrAchievedProgress::where('pr_progress_activity_id', $levelThreeId)->latest()->first();
                            $totalAchievedProgressLevel2 += $totalCurrentProgress->percentage_complete ?? 0;
                        }
                    }
                    //check if level two sum is 0 Its means it is Level 2 Heading
                    if ($levelTwoSum === 0) {

                        $customData1->push([
                            'id' => $levelTwo->id,
                            'pr_detail_id' => $levelTwo->pr_detail_id,
                            'level' => $levelTwo->level,
                            'name' => $levelTwo->name,
                            'weightage' =>  $levelTwo->weightage,
                            'original_weightage' => $levelTwo->weightage,
                            'belong_to_activity' => $levelTwo->belong_to_activity,
                            'progress_achived' => $totalAchievedProgressLevel2,
                            'last_updated_progress' =>  $lastUpdateProgress2,
                        ]);
                    } else {
                        //Otherwise it is last level
                        $customData1->push([
                            'id' => $levelTwo->id,
                            'pr_detail_id' => $levelTwo->pr_detail_id,
                            'level' => $levelTwo->level,
                            'name' => $levelTwo->name,
                            'weightage' =>  $levelTwoSum,
                            'original_weightage' => $levelTwo->weightage,
                            'belong_to_activity' => $levelTwo->belong_to_activity,
                            'progress_achived' => $totalAchievedProgressLevel2,
                            'last_updated_progress' =>  $lastUpdateProgress2,
                        ]);
                    }


                    //Only work if Level Three exist otherwiese not work
                    $levelThrees =  PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 3)->where('belong_to_activity', $levelTwo->id)->get();

                    foreach ($levelThrees as $levelThree) {

                        //get sub activity achived progress
                        $latestProgress = PrAchievedProgress::where('pr_progress_activity_id', $levelThree->id)->latest()->first();
                        $lastUpdateProgress = '';
                        if ($latestProgress) {
                            $totalProgress = $latestProgress->percentage_complete;
                            $lastUpdateProgress = $latestProgress->date;
                        } else {
                            $totalProgress = 0.0;
                        }
                        $customData1->push([
                            'id' => $levelThree->id,
                            'pr_detail_id' => $levelThree->pr_detail_id,
                            'level' => $levelThree->level,
                            'name' => $levelThree->name,
                            'weightage' =>  $levelThree->weightage,
                            'original_weightage' => $levelThree->weightage,
                            'belong_to_activity' => $levelThree->belong_to_activity,
                            'progress_achived' => $totalProgress,
                            'last_updated_progress' =>  $lastUpdateProgress,
                        ]);
                    }
                }
            }
        } else {
            $progressActivities = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 1)->get();
            foreach ($progressActivities as $progressActivity) {
                //get activity achived progress
                $latestProgress = PrAchievedProgress::where('pr_progress_activity_id', $progressActivity->id)->latest()->first();
                $lastUpdateProgress = '';
                if ($latestProgress) {
                    $totalProgress = $latestProgress->percentage_complete;
                    $lastUpdateProgress = $latestProgress->date;
                } else {
                    $totalProgress = 0.0;
                }
                $customData1->push([
                    'id' => $progressActivity->id,
                    'pr_detail_id' => $progressActivity->pr_detail_id,
                    'level' => $progressActivity->level,
                    'name' => $progressActivity->name,
                    'weightage' =>  $progressActivity->weightage,
                    'original_weightage' => $progressActivity->weightage,
                    'belong_to_activity' => $progressActivity->belong_to_activity,
                    'progress_achived' => $totalProgress,
                    'last_updated_progress' =>  $lastUpdateProgress,
                ]);
            }
        }


        if ($request->ajax()) {

            //Datatabe give collection for display
            return DataTables::of($customData1)
                ->editColumn('name', function ($row) {

                    if ($row['level'] === 1 && $row['original_weightage'] === 0.0) {
                        $btn = "<h2 style='color:red'>" . $row['name'] . "</h2>";
                    } else if ($row['original_weightage'] === 0.0 && $row['level'] === 2) {
                        $btn = "<h3 style='color:blue'>" . $row['name'] . "</h3>";
                    } else {
                        $btn = $row['name'];
                    }
                    return $btn;
                })
                ->addColumn('Date', function ($row) {

                    if ($row['weightage'] === $row['original_weightage']) {
                        $btn = ' <input type="text" name="input_progress" id="date' . $row['id'] . '" class="form-control date_input" data-validation="required" readonly>';
                    } else {
                        $btn = '';
                    }
                    return $btn;
                })
                ->addColumn('Progress', function ($row) {

                    if ($row['weightage'] === $row['original_weightage']) {
                        $btn = '<input type="text" name="progress" id="progress' . $row['id'] . '" class="form-control notCapital progressInput" data-validation="required">';
                    } else {
                        $btn = '';
                    }

                    return $btn;
                })
                ->addColumn('Save', function ($row) {
                    if ($row['weightage'] === $row['original_weightage']) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row['id'] . '" data-original-title="Save" class="save btn btn-success btn-sm saveProgress">Save</a>';
                    } else {
                        $btn = '';
                    }

                    return $btn;
                })
                ->addColumn('Detail', function ($row) {
                    if ($row['weightage'] === $row['original_weightage']) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row['id'] . '" data-original-title="Delete" class="btn btn-primary btn-sm deleteModal">Detail</a>';
                    } else {
                        $btn = '';
                    }

                    return $btn;
                })

                ->rawColumns(['Date', 'Progress', 'Save', 'Detail', 'name'])
                ->make(true);
        }
        // dd($customData1);
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
