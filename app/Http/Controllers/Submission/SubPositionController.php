<?php

namespace App\Http\Controllers\Submission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Hr\HrEmployee;
use App\Models\Submission\Submission;
use App\Models\Submission\SubPosition;
use App\Models\Submission\SubNominatePerson;
use App\Models\Submission\SubManMonth;
use App\Models\Submission\SubCv;
use App\Helper\DocxConversion;
use DB;
use DataTables;



class SubPositionController extends Controller
{
    public function index(){
    	$employees = HrEmployee::select(['id','first_name','last_name','employee_no'])->get();
	    $view =  view('submission.position.create',compact('employees'))->render();
	    return response()->json($view);
	}

	public function create(Request $request){

		if($request->ajax()){
   			$data = SubPosition::where('submission_id',session('submission_id'))->orderBy('id','desc')->get();
   			return DataTables::of($data)
   			->addColumn('Edit', function($data){
                $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Edit" class="btn btn-success btn-sm editSubmissionPosition">Edit</a>';

                return $button;

            })
            ->addColumn('Delete', function($data){
                   
                 $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteSubmissionPosition">Delete</a>';
                 return $button;
                    	
            })
            ->addColumn('name', function($data){  
                    return $data->subNominatePerson->name??'';
            })
            ->addColumn('man_month', function($data){  
                    return $data->subManMonth->man_month??'';
            })
            ->addColumn('cv', function($data){  
                
                if($data->subCv){
	                if($data->subCv->extension == 'pdf'){    
	            		 $cv ='<img src="'.asset('Massets/images/document.png').'" href="'.url(isset($data->subCv->file_name)?'/storage/'.$data->subCv->path.$data->subCv->file_name:'Massets/images/document.png').'" class="img-round picture-container ViewPDF"  id="ViewPDF'.$data->id.'" width=50>';
	            		 return $cv;
	            	}else{
	            		$cv ='<a href="'.asset('storage/'.$data->subCv->path.$data->subCv->file_name.'Massets/images/document.png').'" width=30><i class="fa fa-download" aria-hidden="true"></i>Download</a>';
	            		return $cv;
	            	}
	            }else{

                    return '';
	            }
            })

            ->rawColumns(['Edit','Delete','name','man_month','cv'])
            ->make(true);
   		}
	}

	public function store(Request $request){
	    $input = $request->all();
	        $input['submission_id']=session('submission_id');

	        DB::transaction(function () use ($input, $request){  

	        	$subPosition = SubPosition::updateOrCreate(['id' => $input['sub_position_id']],$input); 
	        	$input['sub_position_id']=$subPosition->id;

	        	if($request->filled('name')){

	        		$subNominatePerson= SubNominatePerson::where('sub_position_id',$input['sub_position_id'])->first();
	        		SubNominatePerson::updateOrCreate(['id' => $subNominatePerson->id??''],$input);
	        	}else{
	        		$subNominatePerson= SubNominatePerson::where('sub_position_id',$input['sub_position_id'])->first();
		       		if($subNominatePerson){
		       			 SubNominatePerson::findOrFail($subNominatePerson->id)->delete();  
		       		}
	        	}

	        	if($request->filled('man_month')){
	        		$subManMonth= SubManMonth::where('sub_position_id',$input['sub_position_id'])->first();
	        		SubManMonth::updateOrCreate(['id' => $subManMonth->id??''],$input);
	        	}else{
	        		$subManMonth= SubManMonth::where('sub_position_id',$input['sub_position_id'])->first();
		       		if($subManMonth){
		       			 SubManMonth::findOrFail($subManMonth->id)->delete();  
		       		}
	        	}

	        	if($request->hasFile('cv')){
	        		$submission=Submission::find(session('submission_id'));
	        		$extension = request()->cv->getClientOriginalExtension();
					$fileName =strtolower(preg_replace('/[^a-zA-Z0-9_ -]/s','', request()->position)).'-'. time().'.'.$extension;
					$folderName = "submission/".$submission->submission_no."/";
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

					$attachment['file_name']=$fileName;
					$attachment['size']=$request->file('cv')->getSize();
					$attachment['path']=$folderName;
					$attachment['extension']=$extension;
					$attachment['sub_position_id']=$subPosition->id;

					$subCv=SubCv::where('sub_position_id',$input['sub_position_id'])->first();
					if($subCv){
		       			$path = public_path('storage/'.$subCv->path.$subCv->file_name);
			       			if(File::exists($path)){
	                    	File::delete($path);
	                		}	 
		       		}
					SubCv::updateOrCreate(['id' => $subCv->id??''],$attachment);
		        }

		        if($request->filled('cv_delete')){
		        	$subCv= SubCv::where('sub_position_id',$input['sub_position_id'])->first();
		       		if($subCv){
		       			$path = public_path('storage/'.$subCv->path.$subCv->file_name);
		       			 SubCv::findOrFail($subCv->id)->delete();
		       			if(File::exists($path)){
                    		File::delete($path);
                		} 
		       		}
		        }


	        }); // end transcation

	    return response()->json(['status'=> 'OK', 'message' => "Data Successfully Saved"]);
	}

	public function edit($id){
		
		$data = SubPosition::with('subManMonth','subNominatePerson','subCv')->find($id);
		return response()->json($data);
	}

	public function destroy($id)
    {
        DB::transaction(function () use ($id) {  
            SubPosition::findOrFail($id)->delete();   
        }); // end transcation

        return response()->json(['success'=>'data  delete successfully.']);
   
    }

}
