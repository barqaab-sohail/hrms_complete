<?php

namespace App\Http\Controllers\Cv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Gender;
use App\Models\Common\Country;
use App\Models\Common\State;
use App\Models\Common\City;
use App\Models\Common\Education;
use App\Models\Common\Membership;
use App\Models\CV\CvSpecialization;
use App\Models\CV\CvDiscipline;
use App\Models\CV\CvStage;
use App\Models\CV\CvDetail;
use App\Models\CV\CvSkill;
use App\Models\CV\CvExperience;
use App\Models\CV\CvContact;
use App\Models\CV\CvPhone;
use App\Models\CV\CvAttachment;
use DB;
use App\Helper\DocxConversion;
use Storage;

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

	public function store(Request $request){
		
		$input = $request->only('full_name','father_name','cnic','foreign_experience','donor_experience','barqaab_employment','comments');
		 if($request->filled('date_of_birth')){
            $input ['date_of_birth']= \Carbon\Carbon::parse($request->date_of_birth)->format('Y-m-d');
            }
         if($request->filled('job_starting_date')){
            $input ['job_starting_date']= \Carbon\Carbon::parse($request->job_starting_date)->format('Y-m-d');
            }
         if($request->filled('cv_submission_date')){
            $input ['cv_submission_date']= \Carbon\Carbon::parse($request->cv_submission_date)->format('Y-m-d');
            }


        //start transaction
		DB::transaction(function () use ($request, $input) {   
		
		//add cv detail
		$cvDetail=CvDetail::create($input);


		//add skill
		foreach ($request->input('skill')as $skill){
			cvSkill::create([
				'cv_detail_id'=> $cvDetail->id,
				'skill_name'=> $skill
				]);
		}
		//add education
			for ($i=0;$i<count($request->input('degree_name'));$i++){
			$educationId = $request->input("degree_name.$i");
			$institute = $request->input("institute.$i");
			$passingYear = $request->input("passing_year.$i");
			
			$cvDetail->hrEducation()->attach($educationId, ['institute'=>$institute, 'passing_year'=>$passingYear]);
			}

			
		//add specialization
			for ($i=0;$i<count($request->input('speciality_name'));$i++){
			$speciality['cv_specialization_id'] = $request->input("speciality_name.$i");
			$speciality['cv_discipline_id'] = $request->input("discipline_name.$i");
			$speciality['cv_stage_id'] = $request->input("stage_name.$i");
			$speciality['cv_detail_id']=$cvDetail->id;
			$speciality['year'] = $request->input("year.$i");
			CvExperience::create($speciality);
			}

		//add membership
			for ($i=0;$i<count($request->input('membership_name'));$i++){
			$membershipId = $request->input("membership_name.$i");
			$membershipNumber = $request->input("membership_number.$i");
			$cvDetail->membership()->attach($membershipId, ['membership_number'=>$membershipNumber]);			
			}

		//add contact
			$contact = $request->only('address','city_id','state_id','country_id','email');
			$contact['cv_detail_id'] = $cvDetail->id;
			$cvContact = CvContact::create($contact);
		
		//add phone	
			for ($i=0;$i<count($request->input('phone'));$i++){
			$phone['phone']= $request->input("phone.$i");
			$phone['cv_contact_id'] = $cvContact->id;
			CvPhone::create($phone);		
			}


		//add attachment
				$extension = request()->cv->getClientOriginalExtension();
				$fileName =strtolower(request()->full_name).'-'. time().'.'.$extension;
				$folderName = "cv/".$cvDetail->id.'-'.strtolower(request()->full_name)."/";
				//store file
				$request->file('cv')->storeAs('public/'.$folderName,$fileName);
				
				$file_path = storage_path('app/public/'.$folderName.$fileName);
			
				$attachment['content']='';
											
					if (($extension == 'doc')||($extension == 'docx')){
						$text = new DocxConversion($file_path);
						$attachment['content']=mb_strtolower($text->convertToText());
					}else if ($extension =='pdf'){
						$reader = new \Asika\Pdf2text;
						$attachment['content'] = mb_strtolower($reader->decode($file_path));
					}

				$attachment['document_name']='Original CV';
				$attachment['file_name']=$fileName;
				$attachment['size']=$request->file('cv')->getSize();
				$attachment['path']=$folderName;
				$attachment['extension']=$extension;
				$attachment['cv_detail_id']=$cvDetail->id;

			CvAttachment::create($attachment);


		});  //end transaction

		return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Saved"]);
				
	}

	public function index (){
       $cvs = CvDetail::with('CvContact')->get();
       
        return view('cv.detail.list', compact('cvs'));

    }


    public function edit($id){

    	session()->put('cv_detail_id', $id);
		$genders = Gender::all();
		$countries = Country::all();
		$degrees = Education::all();
		$memberships = Membership::all();
		$specializations = CvSpecialization::all();
		$disciplines = CvDiscipline::all();
		$stages = CvStage::all();
		$today = \Carbon\Carbon::today();
		$cvId = CvDetail::find($id);
		$states = State::where('country_id', $cvId->cvContact->country_id)->get();
		$cities = City::where('state_id', $cvId->cvContact->state_id)->get();
        return view ('cv.detail.edit',compact('genders','specializations','degrees','disciplines','stages','memberships','countries','states','cities','today','cvId'));
    }

    public function update(Request $request, $id){

	    	$input = $request->only('full_name','father_name','cnic','foreign_experience','donor_experience','barqaab_employment','comments');
			 if($request->filled('date_of_birth')){
	            $input ['date_of_birth']= \Carbon\Carbon::parse($request->date_of_birth)->format('Y-m-d');
	            }
	         if($request->filled('job_starting_date')){
	            $input ['job_starting_date']= \Carbon\Carbon::parse($request->job_starting_date)->format('Y-m-d');
	            }
	         if($request->filled('cv_submission_date')){
	            $input ['cv_submission_date']= \Carbon\Carbon::parse($request->cv_submission_date)->format('Y-m-d');
	            }
	     //start transaction
	    DB::transaction(function () use ($request, $input, $id) {  

	    	//update cv Detail
	    	CvDetail::findOrFail($id)->update($input);

	    	//update Contact
			$contact = $request->only('address','city_id','state_id','country_id','email');
			$contact['cv_detail_id'] = $id;
			$cvContact = CvContact::where('cv_detail_id',$id)->first();	
			CvContact::findOrFail($cvContact->id)->update($contact);



	    	//update phone	
	    	if(count($request->input('phone'))==CvPhone::where('cv_contact_id',$cvContact->id)->count())
	    	{
		    	foreach($request->input('phone') as $num){
		    		
		    		foreach ($num as $key =>$phone){
		    		
		    		$data ['phone'] = $phone;
		    		$data['cv_contact_id'] = $cvContact->id;
		    		$key=trim($key,"'");

					CvPhone::findOrFail($key)->update($data);
					
					}
		    	}
		    }else{
		    	CvPhone::where('cv_contact_id',$cvContact->id)->delete();
			    	foreach($request->input('phone') as $num){
			    		foreach ($num as $key =>$phone){
			    		$key=trim($key,"'");
			    		$data ['phone'] = $phone;
			    		$data['cv_contact_id'] = $cvContact->id;
						CvPhone::create($data);
						}
			    	}

			}

			//Update membership
			$cvDetail= CvDetail::find($id);
			$cvDetail->membership()->detach();	
			if ($request->filled('membership_name')){
				for ($i=0;$i<count($request->input('membership_name'));$i++){
				$membershipId = $request->input("membership_name.$i");
				$numberId = $request->input("membership_number.$i");
				$cvDetail->membership()->attach($membershipId, ['membership_number'=>$numberId]);			
				}
			}

			//Update Experience
			if(count($request->input('speciality_name'))==CvExperience::where('cv_detail_id',$id)->count())
	    	{
		    	for ($i=0;$i<count($request->input('speciality_name'));$i++){
				$experience['cv_specialization_id'] = $request->input("speciality_name.$i");
				$experience['cv_discipline_id'] = $request->input("discipline_name.$i");
				$experience['cv_stage_id'] = $request->input("stage_name.$i");
				$experience['cv_detail_id']=$id;
				$experience['year'] = $request->input("year.$i");
				$cvExperience = CvExperience::where('cv_detail_id',$id)->get();
					foreach($cvExperience as $key => $single){
						
						if($i == $key){
							
							CvExperience::findOrFail($single->id)->update($experience);
						}
					}
				}	
		    }else{
		    	CvExperience::where('cv_detail_id',$id)->delete();
			    	for ($i=0;$i<count($request->input('speciality_name'));$i++){
					$experience['cv_specialization_id'] = $request->input("speciality_name.$i");
					$experience['cv_discipline_id'] = $request->input("discipline_name.$i");
					$experience['cv_stage_id'] = $request->input("stage_name.$i");
					$experience['cv_detail_id']=$id;
					$experience['year'] = $request->input("year.$i");
					CvExperience::create($experience);
					}

			}

			//update education
			$cvDetail->cvEducation()->detach();
			
			for ($i=0;$i<count($request->input('degree_name'));$i++){
			$educationId = $request->input("degree_name.$i");
			$instituteId = $request->input("institute.$i");
			$passingYear = $request->input("passing_year.$i");
			
			$cvDetail->cvEducation()->attach($educationId, ['institute'=>$instituteId, 'passing_year'=>$passingYear]);
			}
			
			//update skill	
	    	if(count($request->input('skill_name'))==CvSkill::where('cv_detail_id',$id)->count())
	    	{
		    	foreach($request->input('skill_name') as $num){
		    		foreach ($num as $key =>$skill){
		    		$data ['skill_name'] = $skill;
		    		$data ['cv_detail_id'] = $id;
		    		$key=trim($key,"'");
					CvSkill::findOrFail($key)->update($data);
					
					}
		    	}
		    }else{
		    	CvSkill::where('cv_detail_id',$id)->delete();
			    	foreach($request->input('skill_name') as $num){
			    		foreach ($num as $key =>$skill){
			    		$data ['skill_name'] = $skill;
			    		$data ['cv_detail_id'] = $id;
						CvSkill::create($data);
						}
			    	}
			}

			//add attachment

			if ($request->hasFile('cv')){
				
				$extension = request()->cv->getClientOriginalExtension();
				$fileName =strtolower(request()->full_name).'-'. time().'.'.$extension;
				
				$path= $cvDetail->cvAttachment->first()->path;
				
				//store file
				$request->file('cv')->storeAs('public/'.$path,$fileName);
				
				$file_path = storage_path('app/public/'.$path.$fileName);



				$attachment['content']='';
											
					if (($extension == 'doc')||($extension == 'docx')){
						$text = new DocxConversion($file_path);
						$attachment['content']=mb_strtolower($text->convertToText());
						
					}else if ($extension =='pdf'){
						$reader = new \Asika\Pdf2text;
						$attachment['content'] = mb_strtolower($reader->decode($file_path));

					}

				$attachment['document_name']='Original CV';
				$attachment['file_name']=$fileName;
				$attachment['size']=$request->file('cv')->getSize();
				//$attachment['path']=$file_path;
				$attachment['extension']=$extension;
				$attachment['cv_detail_id']=$cvDetail->id;

				
				$oldFileName= $cvDetail->cvAttachment->first()->file_name;
				
				$attachment_id = $cvDetail->cvAttachment->first()->id;
				
				CvAttachment::findOrFail($attachment_id)->update($attachment);

				if(Storage::exists('public/'.$path.$oldFileName)){
				unlink(storage_path('app/public/'.$path.$oldFileName));
				}
			}



		});	//end transaction
		
    	return response()->json(['status'=> 'OK', 'message' => "Data Sucessfully Updated"]);

    }

     public function destroy($id)
    {
    
    CvDetail::findOrFail($id)->delete(); 
    return back()->with('success', 'Data successfully deleted');
   
    }



}
