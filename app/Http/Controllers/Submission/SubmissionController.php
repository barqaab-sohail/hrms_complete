<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Client;
use App\Models\Submission\SubType;
use App\Models\Submission\Submission;

class SubmissionController extends Controller
{
    
	public function create(){
    $clients = Client::all();
    $subTypes = SubType::all();
    $submissions = Submission::all();
	
	return view ('submission.submission.create',compact('clients','subTypes','submissions'));


	}


}
