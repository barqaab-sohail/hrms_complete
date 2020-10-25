<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Client;
use App\Models\Submission\SubType;
use App\Models\Submission\Submission;
use App\Models\Project\PrDivision;

class SubmissionController extends Controller
{
    public $subValue=0;



	public function create(){
    $clients = Client::all();
    $subTypes = SubType::all();
    $eoiReferences = Submission::all();
    $divisions = PrDivision::all();
    $submissionCode = 456;
	
	return view ('submission.submission.create',compact('clients','subTypes','eoiReferences','divisions','submissionCode'));


	}


	public function submissionCode(Request $request){

		$code = $request->division . $request->subType . substr(date("Y"),-2);
		$count = 1;
		$submissionCode = $code.$count;

		while(Submission::where('submission_no',$code.$count)->count()>0){
			$count++;
			$submissionCode = $code.$count;
		}

		
		// if($submissionCode == $this->subValue ){
		// 	$submissionCode = 66666666666666;
		// }
		
		// $this->subValue = $submissionCode;

		return response()->json($submissionCode);
                
	}


}
