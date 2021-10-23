<?php

namespace App\Http\Controllers\Project\Progress;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrDetail;

class ActivityController extends Controller
{
    public function chart(){
    	$project = PrDetail::where('id','=',session('pr_detail_id'))->first();
    	if($project->id == 4){
    		return view ('project.charts.progress.progressChart');
    	}
    }
}
