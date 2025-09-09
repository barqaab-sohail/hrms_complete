<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Hr\HrDocumentName;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrPromotion;
use App\Models\Hr\HrPosting;
use App\Models\Hr\HrDocumentation;
use App\Models\Hr\HrDocumentNameDocumentation;
use App\Http\Requests\Hr\DocumentationStore;
use DataTables;
use DB;

class DocumentationController extends Controller
{


    public function show(Request $request, $id)
    {

        $documentNames = HrDocumentName::all();

        if ($request->ajax()) {
            return  view('hr.documentation.create', compact('documentNames', 'id'));
        } else {
            return back()->withError('Please contact to administrator, SSE_JS');
        }
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            //  $data= HrDocumentation::where('hr_employee_id', $request->hrEmployeeId)
            $documentIds = HrDocumentation::where('hr_employee_id', $request->hrEmployeeId)->orderByRaw('ISNULL(document_date), document_date desc')->get();

            $order = array('Joining Report', 'Appointment Letter', 'HR Form', 'CNIC Front', 'CNIC Back', 'Original CV', 'BARQAAB CV', 'Engineering Degree MSc', 'Engineering Degree BSc', 'Educational Documents', 'Experience Certificates', 'PEC Letter', 'PEC Front', 'PEC Back', 'Pec Letter', 'Picture', 'Driving License Front', 'Driving License Back', 'Application', 'Domicile');

            $documentIds = $documentIds->sort(function ($a, $b) use ($order) {
                $pos_a = array_search($a->description, $order);
                $pos_b = array_search($b->description, $order);
                return $pos_a - $pos_b;
            });

            $data = $documentIds->sortByDesc('document_date');
            //->latest()->get();
            return  DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('document', function ($row) {
                    if ($row->extension != 'pdf') {
                        return '<img id="ViewIMG" src="' . $row->full_path . '" href="' . $row->full_path . '" width="30/" style="cursor: pointer;">';
                    } else {
                        return '<img id="ViewPDF" src="https://hrms.barqaab.pk/Massets/images/document.png" href="' . $row->full_path . '" width="30/" style="cursor: pointer;">';
                    }
                })
                ->addColumn('copy_link', function ($row) {

                    return '<a class="copyLink" link="' . $row->tiny_url . '" style="cursor: auto;" title="Click for Copy Link"><img src="https://hrms.barqaab.pk/Massets/images/copyLink.png" width="30"></a>';
                })
                ->addColumn('Edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editDocument">Edit</a>';

                    return $btn;
                })
                ->addColumn('Delete', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteDocument">Delete</a>';

                    return $btn;
                })

                ->rawColumns(['document', 'copy_link', 'Edit', 'Delete'])
                ->make(true);
        }
    }



    public function store(DocumentationStore $request)
    {

        $input = $request->only('hr_document_name_id', 'description', 'document', 'document_id', 'hr_employee_id');

        if ($request->filled('document_date')) {
            $input['document_date'] = \Carbon\Carbon::parse($request->document_date)->format('Y-m-d');
        }

        if (!$request->filled('description')) {
            $input['description'] = HrDocumentName::find($input['hr_document_name_id'])->name;
        }

        $employee = HrEmployee::find($input['hr_employee_id']);
        $employeeName = str_replace(' ', '_', strtolower($employee->full_name));


        DB::transaction(function () use ($request, $input, $employeeName) {

            if ($request->hasFile('document')) {

                $vriableName = time();
                if ($request->filled('custom_name')) {
                    $vriableName = $request->custom_name;
                }

                $extension = request()->document->getClientOriginalExtension();

                $fileName = strtolower(preg_replace('/[^a-zA-Z0-9_ -]/s', '', str_replace(" ", "_", $input['description']))) . $vriableName . '.' . $extension;

                if (HrDocumentation::where('file_name', $fileName)->first()) {
                    $fileName = $input['hr_employee_id'] . '-' . strtolower(preg_replace('/[^a-zA-Z0-9_ -]/s', '', str_replace(" ", "_", $input['description']))) . '-' . $vriableName . time() . '.' . $extension;
                }

                $folderName = "hr/documentation/" . $input['hr_employee_id'] . '-' . $employeeName . "/";
                //store file
                $request->file('document')->storeAs('public/' . $folderName, $fileName);

                $file_path = storage_path('app/public/' . $folderName . $fileName);

                $input['content'] = '';

                if ($extension == 'pdf') {
                    $reader = new \Asika\Pdf2text;
                    $input['content'] = mb_strtolower($reader->decode($file_path));
                }

                $input['file_name'] = $fileName;
                $input['size'] = $request->file('document')->getSize();
                $input['path'] = $folderName;
                $input['extension'] = $extension;
            }
            $input['hr_employee_id'] = $request->hr_employee_id;


            $hrDocumentation = HrDocumentation::updateOrCreate(['id' => $input['document_id']], $input);

            if ($request->hr_document_name_id != 'Other') {
                $hrDocumentNameId = $request->input("hr_document_name_id");
                //hr_employee_id is add due to validtaion before enter into database
                $hrDocumentation->hrDocumentName()->attach($hrDocumentNameId, ['hr_employee_id' => $input['hr_employee_id']]);
            } else {
                $hrDocumentation->hrDocumentName()->detach();
            }


            // check if document is related to promotion update also promotion
            $hrPromotion = HrPromotion::where('hr_documentation_id', $input['document_id'])->first();
            if ($hrPromotion) {
                $input['remarks'] = $input['description'];
                $input['effective_date'] =  $input['document_date'];
                HrPromotion::findOrFail($hrPromotion->id)->update($input);
            }

            // check if document is related to posting update also posting
            $hrPosting = HrPosting::where('hr_documentation_id', $input['document_id'])->first();
            if ($hrPosting) {
                $input['remarks'] = $input['description'];
                $input['effective_date'] =  $input['document_date'];
                HrPosting::findOrFail($hrPosting->id)->update($input);
            }
        });  //end transaction


        return response()->json(['status' => 'OK', 'message' => "Document Successfully Saved"]);
    }



    public function edit($id)
    {
        $hrDocumentation = HrDocumentation::with('hrDocumentName')->find($id);
        return response()->json($hrDocumentation);
    }


    public function destroy($id)
    {

        $hrDocument = HrDocumentation::where('id', $id)->first();

        $hrDocumentPosting = HrPosting::where('hr_documentation_id', $id)->first();
        $hrDocumentPromotion = HrPromotion::where('hr_documentation_id', $id)->first();

        if ($hrDocumentPromotion || $hrDocumentPosting) {

            return response()->json(['status' => 'Not OK', 'message' => "You cannot delete promotion / posting document from here"]);
        } else {

            $path = public_path('storage/' . $hrDocument->path . $hrDocument->file_name);
            if (File::exists($path)) {
                File::delete($path);
            }

            $hrDocument->forceDelete();

            return response()->json(['status' => 'OK', 'message' => "Document Successfully Deleted"]);
        }
    }




    // public function create(Request $request)
    // {

    //     $documentIds = HrDocumentation::where('hr_employee_id', session('hr_employee_id'))->orderBy('document_date', 'desc')->get();

    //     // $documentIds = $documentIds->sortByDesc('document_date');

    //     // $documentIds->all();

    //     // foreach ($documentIds as $id){
    //     //     echo $id->description;
    //     //     echo '<br>';
    //     // }
    //     // dd();


    //     $Ids = $documentIds->pluck('id')->toArray();
    //     //For security checking
    //     session()->put('document_delete_ids', $Ids);



    //     $documentNames = HrDocumentName::all();

    //     if ($request->ajax()) {
    //         $view = view('hr.documentation.create', compact('documentNames'))->render();
    //         return response()->json($view);
    //     } else {
    //         return back()->withError('Please contact to administrator, SSE_JS');
    //     }
    // }

    // public function store(DocumentationStore $request)
    // {

    //     $input = $request->only('hr_document_name_id', 'description', 'document');

    //     if ($request->filled('document_date')) {
    //         $input['document_date'] = \Carbon\Carbon::parse($request->document_date)->format('Y-m-d');
    //     }

    //     if ($request->filled('description')) {
    //         //
    //     } else {
    //         $input['description'] = HrDocumentName::find($input['hr_document_name_id'])->name;
    //     }

    //     $employee = HrEmployee::find(session('hr_employee_id'));
    //     $employeeName = str_replace(' ', '_', strtolower($employee->full_name));


    //     DB::transaction(function () use ($request, $input, $employeeName) {

    //         $extension = request()->document->getClientOriginalExtension();

    //         $fileName = session('hr_employee_id') . '-' . strtolower(preg_replace('/[^a-zA-Z0-9_ -]/s', '', str_replace(" ", "_", $input['description']))) . '-' . time() . '.' . $extension;
    //         $folderName = "hr/documentation/" . session('hr_employee_id') . '-' . $employeeName . "/";
    //         //store file
    //         $request->file('document')->storeAs('public/' . $folderName, $fileName);

    //         $file_path = storage_path('app/public/' . $folderName . $fileName);

    //         $input['content'] = '';

    //         if ($extension == 'pdf') {
    //             $reader = new \Asika\Pdf2text;
    //             $input['content'] = mb_strtolower($reader->decode($file_path));
    //         }

    //         $input['file_name'] = $fileName;
    //         $input['size'] = $request->file('document')->getSize();
    //         $input['path'] = $folderName;
    //         $input['extension'] = $extension;
    //         $input['hr_employee_id'] = session('hr_employee_id');


    //         $hrDocumentation = HrDocumentation::create($input);

    //         if ($request->hr_document_name_id != 'Other') {
    //             $hrDocumentNameId = $request->input("hr_document_name_id");

    //             //hr_employee_id is add due to validtaion before enter into database
    //             $hrDocumentation->hrDocumentName()->attach($hrDocumentNameId, ['hr_employee_id' => session('hr_employee_id')]);
    //         }
    //     });  //end transaction

    //     return response()->json(['status' => 'OK', 'message' => "Data Successfully Saved"]);
    // }

    // public function edit(Request $request, $id)
    // {
    //     //For security checking
    //     session()->put('document_edit_id', $id);

    //     $documentNames = HrDocumentName::all();
    //     $data = HrDocumentation::find($id);
    //     $documentNameExist = HrDocumentNameDocumentation::where('hr_documentation_id', $data->id)->first();

    //     if ($request->ajax()) {
    //         return view('hr.documentation.edit', compact('documentNames', 'data', 'documentNameExist'));
    //     } else {
    //         return back()->withError('Please contact to administrator, SSE_JS');
    //     }
    // }



    // public function update(DocumentationStore $request, $id)
    // {
    //     //ensure client end id is not changed
    //     if ($id != session('document_edit_id')) {
    //         return response()->json(['status' => 'Not OK', 'message' => "Security Breach. No Data Change "]);
    //     }

    //     $input = $request->only('hr_document_name_id', 'description', 'document', 'document_date');
    //     $data = HrDocumentation::find($id);

    //     if ($request->filled('document_date')) {
    //         $input['document_date'] = \Carbon\Carbon::parse($request->document_date)->format('Y-m-d');
    //     }

    //     if ($request->hr_document_name_id != 'Other') {
    //         $input['description'] = HrDocumentName::find($input['hr_document_name_id'])->name;
    //     } else {
    //         $data->hrDocumentName()->detach();
    //     }

    //     $employee = HrEmployee::find(session('hr_employee_id'));
    //     $employeeFullName = strtolower($employee->first_name) . '_' . strtolower($employee->last_name);


    //     DB::transaction(function () use ($request, $input, $employeeFullName, $data, $id) {

    //         if ($request->hasFile('document')) {

    //             $extension = request()->document->getClientOriginalExtension();

    //             $fileName = session('hr_employee_id') . '-' . strtolower(preg_replace('/[^a-zA-Z0-9_ -]/s', '', str_replace(" ", "_", $input['description']))) . '-' . time() . '.' . $extension;
    //             $folderName = "hr/documentation/" . session('hr_employee_id') . '-' . $employeeFullName . "/";

    //             //store file
    //             $request->file('document')->storeAs('public/' . $folderName, $fileName);

    //             $file_path = storage_path('app/public/' . $folderName . $fileName);

    //             $input['content'] = '';

    //             if ($extension == 'pdf') {
    //                 $reader = new \Asika\Pdf2text;
    //                 $input['content'] = mb_strtolower($reader->decode($file_path));
    //             }

    //             $input['file_name'] = $fileName;
    //             $input['size'] = $request->file('document')->getSize();
    //             $input['path'] = $folderName;
    //             $input['extension'] = $extension;
    //             $input['hr_employee_id'] = session('hr_employee_id');


    //             HrDocumentation::findOrFail($id)->update($input);
    //             if ($request->hr_document_name_id != 'Other') {
    //                 $hrDocumentNameId = $request->input("hr_document_name_id");

    //                 //hr_employee_id is add due to validtaion before enter into database
    //                 $data->hrDocumentName()->detach();
    //                 $data->hrDocumentName()->attach($hrDocumentNameId, ['hr_employee_id' => session('hr_employee_id')]);
    //             }
    //         } else {

    //             HrDocumentation::findOrFail($id)->update($input);
    //             if ($request->hr_document_name_id != 'Other') {
    //                 $hrDocumentNameId = $request->input("hr_document_name_id");

    //                 //hr_employee_id is add due to validtaion before enter into database
    //                 $data->hrDocumentName()->detach();
    //                 $data->hrDocumentName()->attach($hrDocumentNameId, ['hr_employee_id' => session('hr_employee_id')]);
    //             }
    //         }

    //         $hrPromotion = HrPromotion::where('hr_documentation_id', $id)->first();
    //         if ($hrPromotion) {
    //             $input['remarks'] = $input['description'];
    //             $input['effective_date'] =  $input['document_date'];
    //             HrPromotion::findOrFail($hrPromotion->id)->update($input);
    //         }

    //         $hrPosting = HrPosting::where('hr_documentation_id', $id)->first();
    //         if ($hrPosting) {
    //             $input['remarks'] = $input['description'];
    //             $input['effective_date'] =  $input['document_date'];
    //             HrPosting::findOrFail($hrPosting->id)->update($input);
    //         }
    //     });  //end transaction

    // $data = HrDocumentation::find($id);
    // $input = $request->only('hr_document_name_id','description','document');

    //     if ($request->filled('description')) {

    //     }else{
    //         $input['description']= HrDocumentName::find($input['hr_document_name_id'])->name;
    //     }

    //     $employee = HrEmployee::find(session('hr_employee_id'));
    //     $employeeFullName = strtolower($employee->first_name) .'_'.strtolower($employee->last_name);



    // DB::transaction(function () use ($request, $input, $employeeFullName, $data, $id, &$hrDocumentNameId) { 


    //     if ($request->hasFile('document')){
    //         $extension = request()->document->getClientOriginalExtension();
    //         $fileName =session('hr_employee_id').'-'.$input['description'].'-'. time().'.'.$extension;
    //         $folderName = $data->path;
    //         //store file
    //         $request->file('document')->storeAs('public/'.$folderName,$fileName);

    //         $file_path = storage_path('app/public/'.$folderName.$fileName);

    //         $input['content']='';

    //             if ($extension =='pdf'){
    //                 $reader = new \Asika\Pdf2text;
    //                 $input['content'] = mb_strtolower($reader->decode($file_path));
    //             }

    //         $input['file_name']=$fileName;
    //         $input['size']=$request->file('document')->getSize();
    //         //$input['path']=$folderName;
    //         $input['extension']=$extension;
    //         $input['hr_employee_id']=session('hr_employee_id');

    //         HrDocumentation::findOrFail($id)->update($input);
    //     }else{

    //         HrDocumentation::findOrFail($id)->update(['description'=>$input['description']]);
    //         if($request->hr_document_name_id!='Other'){
    //         $hrDocumentNameId = $request->input("hr_document_name_id");

    //         //hr_employee_id is add due to validtaion before enter into database
    //         $data->hrDocumentName()->updateExistingPivot($hrDocumentNameId, ['hr_employee_id'=>session('hr_employee_id')]);   
    //         }
    //     }


    // });  //end transaction

    //     return response()->json(['status' => 'OK', 'message' => "Data Successfully Updated"]);
    // }



    // public function destroy($id)
    // {

    //     if (!in_array($id, session('document_delete_ids'))) {
    //         return response()->json(['status' => 'Not OK', 'message' => "Security Breach. No Data Change "]);
    //     }

    //     $hrDocument = HrDocumentation::where('id', $id)->where('hr_employee_id', session('hr_employee_id'))->first();



    //     $hrDocumentPosting = HrPosting::where('hr_documentation_id', $id)->first();
    //     $hrDocumentPromotion = HrPromotion::where('hr_documentation_id', $id)->first();

    //     if ($hrDocumentPromotion || $hrDocumentPosting) {

    //         return response()->json(['status' => 'Not OK', 'message' => "You cannot delete this document from here"]);
    //     } else {

    //         $path = public_path('storage/' . $hrDocument->path . $hrDocument->file_name);
    //         if (File::exists($path)) {
    //             File::delete($path);
    //         }

    //         $hrDocument->forceDelete();

    //         return response()->json(['status' => 'OK', 'message' => "Data Successfully Deleted"]);
    //     }
    // }

    public function refreshTable()
    {

        $documentIds = HrDocumentation::where('hr_employee_id', session('hr_employee_id'))->orderByRaw('ISNULL(document_date), document_date desc')->get();

        $order = array('Joining Report', 'Appointment Letter', 'HR Form', 'CNIC Front', 'CNIC Back', 'Original CV', 'BARQAAB CV', 'Engineering Degree MSc', 'Engineering Degree BSc', 'Educational Documents', 'Experience Certificates', 'PEC Letter', 'PEC Front', 'PEC Back', 'Pec Letter', 'Picture', 'Driving License Front', 'Driving License Back', 'Application', 'Domicile');

        $documentIds = $documentIds->sort(function ($a, $b) use ($order) {
            $pos_a = array_search($a->description, $order);
            $pos_b = array_search($b->description, $order);
            return $pos_a - $pos_b;
        });

        $documentIds = $documentIds->sortByDesc('document_date');
        // foreach ($documentIds as $document){
        //     echo $document->document_date . '<br>';
        // }
        // dd();

        $Ids = $documentIds->pluck('id')->toArray();
        //For security checking
        session()->put('document_delete_ids', $Ids);

        return view('hr.documentation.list', compact('documentIds'));
    }
}
