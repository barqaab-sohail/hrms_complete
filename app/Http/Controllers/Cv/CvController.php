<?php

namespace App\Http\Controllers\Cv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Gender;
use App\Models\Common\Country;
use App\Models\Common\Education;
use App\Models\Common\Membership;
use App\Models\CV\CvSpecialization;
use App\Models\CV\CvDiscipline;
use App\Models\CV\CvStage;

class CvController extends Controller
{
    
    public function create(){
		session()->put('cv_detail_id', '');
		$genders = Gender::all();
		$countries = Country::all();
		$degrees = Education::all();
		$memberships = Membership::all();
		$specializations = CvSpecialization::all();
		$disciplines = CvDiscipline::all();
		$stages = CvStage::all();
		
		$today = \Carbon\Carbon::today();

		//return view ('bio-data.test',compact('genders'));
		return view ('cv.detail.create',compact('genders','specializations','degrees','disciplines','stages','memberships','countries','today'));
	}


}
