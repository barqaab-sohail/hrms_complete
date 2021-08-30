<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectProgressController extends Controller
{
    public function chart(){
    	
    	return view ('project.charts.progress.progressChart');


    }
}
