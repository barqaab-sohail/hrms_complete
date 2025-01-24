<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Common\Right;
use App\Http\Requests\Project\Rights\ProjectRightsStore;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Models\Project\PrDetail;
use App\Models\Project\PrRight;
use App\Models\Admin\Permission;
use App\User;
use DB;
use DataTables;

class ProjectRightController extends Controller
{


    public function create(Request $request)
    {
        $customData1 = new Collection;
        //$customData1->push(['name' => 'sohail afzal', 'father name' => 'Muhammad Afzal']);

        $firstHeadings = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('weightage', 'NULL')->get();

        $projectLevel = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->max('level');
        $progressActivities = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 1)->get();


        if ($projectLevel > 1) {
            $levelOnes = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 1)->get();
            foreach ($levelOnes as $levelOne) {
                $levelTwoIds = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 2)->where('belong_to_activity', $levelOne->id)->pluck('id')->toArray();
                $levelOneSum = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->whereIn('belong_to_activity', $levelTwoIds)->sum('weightage');
                if ($levelOneSum === 0) {
                    $levelTwoS = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('belong_to_activity', $levelOne->id)->sum('weightage');
                    $customData1->push([
                        'id' => $levelOne->id,
                        'pr_detail_id' => $levelOne->pr_detail_id,
                        'level' => $levelOne->level,
                        'name' => $levelOne->name,
                        'weightage' => $levelTwoS,
                        'belong_to_activity' => $levelOne->belong_to_activity,
                        'progress_achived' => '',
                        'last_updated_progress' => '',
                    ]);
                    // echo $levelOne->name . '--' . $levelTwoS . '<br>';
                } else {
                    $customData1->push([
                        'id' => $levelOne->id,
                        'pr_detail_id' => $levelOne->pr_detail_id,
                        'level' => $levelOne->level,
                        'name' => $levelOne->name,
                        'weightage' => $levelOneSum,
                        'belong_to_activity' => $levelOne->belong_to_activity,
                        'progress_achived' => '',
                        'last_updated_progress' => '',
                    ]);
                    //$customData1->push($levelOne);
                    //echo $levelOne->name . '- - ' . $levelOneSum . '<br>';
                }
                $levelTwos = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 2)->where('belong_to_activity', $levelOne->id)->get();
                foreach ($levelTwos as $levelTwo) {
                    $levelTwoSum = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('belong_to_activity', $levelTwo->id)->sum('weightage');
                    //get sub activity achived progress
                    $latestProgress2 = PrAchievedProgress::where('pr_progress_activity_id', $levelTwo->id)->latest()->first();
                    $lastUpdateProgress2 = '';
                    if ($latestProgress2) {
                        $totalProgress2 = $latestProgress2->percentage_complete;
                        $lastUpdateProgress2 = $latestProgress2->date;
                    } else {
                        $totalProgress2 = 0.0;
                    }

                    if ($levelTwoSum === 0) {

                        $customData1->push([
                            'id' => $levelTwo->id,
                            'pr_detail_id' => $levelTwo->pr_detail_id,
                            'level' => $levelTwo->level,
                            'name' => $levelTwo->name,
                            'weightage' => $levelTwo->weightage,
                            'belong_to_activity' => $levelTwo->belong_to_activity,
                            'progress_achived' => $totalProgress2,
                            'last_updated_progress' => $lastUpdateProgress2,
                        ]);
                        //$customData1->push($levelOne);
                        //echo '-------' . $levelTwo->name . ' - ' . $levelTwo->weightage . '<br>';
                    } else {
                        $customData1->push([
                            'id' => $levelTwo->id,
                            'pr_detail_id' => $levelTwo->pr_detail_id,
                            'level' => $levelTwo->level,
                            'name' => $levelTwo->name,
                            'weightage' => $levelTwoSum,
                            'belong_to_activity' => $levelTwo->belong_to_activity,
                            'progress_achived' => $totalProgress2,
                            'last_updated_progress' => $lastUpdateProgress2,
                        ]);
                        // $customData1->push($levelOne);
                        //echo '-------' . $levelTwo->name . '- - ' . $levelTwoSum . '<br>';
                    }

                    $levelThrees = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 3)->where('belong_to_activity', $levelTwo->id)->get();
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
                            'weightage' => $levelThree->weightage,
                            'belong_to_activity' => $levelThree->belong_to_activity,
                            'progress_achived' => $totalProgress,
                            'last_updated_progress' => $lastUpdateProgress,
                        ]);
                        //echo '----------------------' . $levelThree->name . '- - ' . $levelThree->weightage . '<br>';
                    }
                }
            }
        }
        //dd($customData1);




        // $projectLevel = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->max('level');
        // $progressActivities = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 1)->get();


        // if ($projectLevel > 1) {
        // $levelOnes = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 1)->get();
        // foreach ($levelOnes as $levelOne) {
        // $Data1->push($levelOne);
        // $levelTwoIds = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 2)->where('belong_to_activity', $levelOne->id)->pluck('id')->toArray();
        // $levelOneSum = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->whereIn('belong_to_activity', $levelTwoIds)->sum('weightage');
        // if ($levelOneSum === 0) {

        // $levelTwoS = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('belong_to_activity', $levelOne->id)->sum('weightage');
        // echo $levelOne->name . '--' . $levelTwoS . '<br>';
        // } else {
        // echo $levelOne->name . '- - ' . $levelOneSum . '<br>';
        // }

        // $levelTwos = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 2)->where('belong_to_activity', $levelOne->id)->get();
        // foreach ($levelTwos as $levelTwo) {
        // $levelTwoSum = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('belong_to_activity', $levelTwo->id)->sum('weightage');
        // if ($levelTwoSum === 0) {
        // echo '-------' . $levelTwo->name . ' - ' . $levelTwo->weightage . '<br>';
        // } else {
        // echo '-------' . $levelTwo->name . '- - ' . $levelTwoSum . '<br>';
        // }

        // $levelThrees = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 3)->where('belong_to_activity', $levelTwo->id)->get();
        // foreach ($levelThrees as $levelThree) {
        // echo '----------------------' . $levelThree->name . '- - ' . $levelThree->weightage . '<br>';
        // }
        // }
        // }
        // } else {
        // foreach ($progressActivities as $progressActivity) {
        // echo $progressActivity->name . '- - ' . $progressActivity->weightage . '<br>';
        // }
        // }


        if ($request->ajax()) {

            $customData = new Collection;

            $firstHeadings = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('weightage', 'NULL')->get();

            $projectLevel = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->max('level');
            $progressActivities = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 1)->get();


            if ($projectLevel > 1) {
                $levelOnes = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 1)->get();
                foreach ($levelOnes as $levelOne) {
                    $levelTwoIds = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('level', 2)->where('belong_to_activity', $levelOne->id)->pluck('id')->toArray();
                    $levelOneSum = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->whereIn('belong_to_activity', $levelTwoIds)->sum('weightage');
                    if ($levelOneSum === 0) {
                        $levelTwoS = PrProgressActivity::where('pr_detail_id', session('pr_detail_id'))->where('belong_to_activity', $levelOne->id)->sum('weightage');
                        // $customData1->push($levelOne);
                        // echo $levelOne->name . '--' . $levelTwoS . '<br>';
                    } else {
                        // $customData1->push($levelOne);
                        //echo $levelOne->name . '- - ' . $levelOneSum . '<br>';
                    }
                }
            }

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
                        $firstHeadingProgress = PrAchievedProgress::where('pr_progress_activity_id', $id)->latest()->first();
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
            return DataTables::of($customData1)
                ->editColumn('name', function ($row) {

                    if ($row['belong_to_activity'] === NULL) {
                        $btn = "<h3 style='color:red'>" . $row['name'] . "</h3>";
                    } else {
                        $btn = $row['name'];
                    }
                    return $btn;
                })
                ->editColumn('last_updated_progress', function ($row) {
                    if ($row['belong_to_activity'] != NULL) {
                        $btn = $row['last_updated_progress'];
                    } else {
                        $btn = '';
                    }
                    return '';
                })
                ->addColumn('Date', function ($row) {

                    if ($row['belong_to_activity'] != NULL) {
                        $btn = ' <input type="text" name="input_progress" id="date' . $row['id'] . '" class="form-control date_input" data-validation="required" readonly>';
                    } else {
                        $btn = '';
                    }
                    return $btn;
                })
                ->addColumn('Progress', function ($row) {

                    if ($row['belong_to_activity'] != NULL) {
                        $btn = '<input type="text" name="progress" id="progress' . $row['id'] . '" class="form-control notCapital progressInput" data-validation="required">';
                    } else {
                        $btn = '';
                    }

                    return $btn;
                })
                ->addColumn('Save', function ($row) {
                    if ($row['belong_to_activity'] != NULL) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row['id'] . '" data-original-title="Save" class="save btn btn-success btn-sm saveProgress">Save</a>';
                    } else {
                        $btn = '';
                    }

                    return $btn;
                })
                ->addColumn('Detail', function ($row) {
                    if ($row['belong_to_activity'] != NULL) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row['id'] . '" data-original-title="Delete" class="btn btn-primary btn-sm deleteModal">Detail</a>';
                    } else {
                        $btn = '';
                    }

                    return $btn;
                })

                ->rawColumns(['Date', 'Progress', 'Save', 'Detail', 'name', 'last_updated_progress'])
                ->make(true);
        }


        // $prProgressActivities = PrProgressActivity::where('pr_detail_id',session('pr_detail_id'))->get();
        // $view = view('project.progress.achived.create',compact('prProgressActivities'))->render();
        // return response()->json($view);

    }
}
