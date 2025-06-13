<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Project\DocumentStore;
use App\Models\Project\PrFolderName;
use App\Models\Project\PrDocument;
use App\Models\Project\PrDocumentContent;
use App\Models\Hr\HrEmployee;
use App\Models\Hr\HrDocumentation;
use App\Models\Hr\HrDocumentationProject;
use App\Helper\DocxConversion;
use DataTables;
use DB;

class ProjectDocumentController extends Controller
{



    public function documentDataTable($data)
    {
        return  DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('document', function ($row) {
                if ($row->extension != 'pdf') {
                    return '<img id="ViewIMG" src="' . $row->full_path . '" href="' . $row->full_path . '" width="30/" style="cursor: pointer;">';
                } else {
                    return '<img id="ViewPDF" src="https://hrms.barqaab.pk/Massets/images/document.png" href="' . $row->full_path . '" width="30/" style="cursor: pointer;">';
                }
            })
            ->editColumn('description', function ($row) {
                // Trim to 50 chars and add ellipsis if longer
                $shortDescription = strlen($row->description) > 50
                    ? substr($row->description, 0, 50) . '...'
                    : $row->description;

                return '<span title="' . htmlspecialchars($row->description, ENT_QUOTES) . '">'
                    . htmlspecialchars($shortDescription)
                    . '</span>';
            })
            ->addColumn('copy_link', function ($row) {
                return '<a class="copyLink" link="' . $row->full_path . '" style="cursor: auto;" title="Click for Copy Link"><img src="https://hrms.barqaab.pk/Massets/images/copyLink.png" width="30"></a>';
            })
            ->addColumn('Edit', function ($row) {

                $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editDocument">Edit</a>';

                return $btn;
            })
            ->addColumn('Delete', function ($row) {

                $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteDocument">Delete</a>';

                return $btn;
            })

            ->rawColumns(['document', 'description', 'copy_link', 'Edit', 'Delete'])
            ->make(true);
    }


    public function showFolder($folderId, $prDetailId)
    {
        $data = PrDocument::where('pr_detail_id', $prDetailId)->where('pr_folder_name_id', $folderId)->orderByRaw('ISNULL(document_date), document_date desc')->get();
        return $this->documentDataTable($data);
    }

    // public function show($id)
    // {
    //     $folderName = PrFolderName::find($id);
    //     $documentIds = PrDocument::where('pr_detail_id', session('pr_detail_id'))
    //         ->where('pr_folder_name_id', $id)->get();
    //     $Ids = $documentIds->pluck('id')->toArray();
    //     //For security checking
    //     session()->put('pr_document_delete_ids', $Ids);

    //     return view('project.document.list', compact('documentIds', 'folderName'));
    // }

    public function show(Request $request, $id)
    {
        $prFolderNames = PrFolderName::all();
        $documentIds = PrDocument::where('pr_detail_id', $id)->get();
        if ($request->ajax()) {
            $view = view('project.document.create', compact('prFolderNames', 'documentIds', 'id'))->render();
            return response()->json($view);
        } else {
            return back()->withError('Please contact to administrator, SSE_JS');
        }
        //->where('pr_folder_name_id', $id)
        // $Ids = $documentIds->pluck('id')->toArray();

    }



    public function create(Request $request)
    {

        if ($request->ajax()) {

            $data = PrDocument::where('pr_detail_id', $request->prDetailId)->orderByRaw('ISNULL(document_date), document_date desc')->get();
            //->latest()->get();
            return $this->documentDataTable($data);
        }
    }

    // public function create(Request $request)
    // {

    //     $prFolderNames = PrFolderName::all();
    //     $documentIds = PrDocument::where('pr_detail_id', $request->pr_detail_id)->get();
    //     $employees = HrEmployee::where('hr_status_id', 1)->get();

    //     if ($request->ajax()) {
    //         $view = view('project.document.create', compact('prFolderNames', 'documentIds', 'employees'))->render();
    //         return response()->json($view);
    //     } else {
    //         return back()->withError('Please contact to administrator, SSE_JS');
    //     }
    // }

    public function reference(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');
            $data = DB::table('pr_documents')
                ->where('pr_detail_id', session('pr_detail_id'))
                ->where('reference_no', 'LIKE', "%{$query}%")
                ->take(3)->get();
            $output = '<ul style="display:block; position:relative;list-style-type:none;">';
            foreach ($data as $row) {
                $output .= '
           <li style="color: red;">' . $row->reference_no . '</li>
           ';
            }
            $output .= '</ul>';
            echo $output;
        }
    }

    public function store(DocumentStore $request)
    {

        $input = $request->only('pr_folder_name_id', 'reference_no', 'description', 'document', 'pr_document_id', 'pr_detail_id');

        if ($request->filled('document_date')) {
            $input['document_date'] = \Carbon\Carbon::parse($request->document_date)->format('Y-m-d');
        }

        DB::transaction(function () use ($request, $input) {

            // Document_id and document - Update and have document
            //No document_id and document - Create document and have document
            //Document_id and No document - Update and no document


            // Only document Avaiable
            if ($request->hasFile('document')) {
                $extension = request()->document->getClientOriginalExtension();

                $fileName = strtolower(preg_replace('/[^a-zA-Z0-9_ -]/s', '', str_replace(" ", "_", $input['description']))) . '-' . time() . '.' . $extension;
                $folderName = "project/" . $request->pr_detail_id . "/";
                //store file
                $request->file('document')->storeAs('public/' . $folderName, $fileName);

                $file_path = storage_path('app/public/' . $folderName . $fileName);

                $input['content'] = '';

                if (($extension == 'doc') || ($extension == 'docx')) {
                    $text = new DocxConversion($file_path);
                    $input['content'] = mb_strtolower($text->convertToText());
                } else if ($extension == 'pdf') {
                    $reader = new \Asika\Pdf2text;
                    $input['content'] = mb_strtolower($reader->decode($file_path));
                }

                $input['file_name'] = $fileName;
                $input['size'] = $request->file('document')->getSize();
                $input['path'] = $folderName;
                $input['extension'] = $extension;
                $input['pr_detail_id'] = $request->pr_detail_id;

                //check create new data or update data
                if ($request->pr_document_id) {
                    //update record
                    $prDocument = PrDocument::findOrFail($request->pr_document_id);
                    $path = public_path('storage/' . $prDocument->path . $prDocument->file_name);
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                    $prDocument->update($input);
                    $input['pr_document_id'] = $request->pr_document_id;
                } else {

                    //create record
                    $prDocument = PrDocument::create($input);
                    $input['pr_document_id'] = $prDocument->id;
                }

                if (strlen($input['content']) > 50 && strlen($input['content']) < 16777200) {

                    PrDocumentContent::updateOrCreate(
                        ['pr_document_id' => $input['pr_document_id']],       //It is find and update 
                        $input
                    );
                }
            } else {

                $prDocument = PrDocument::findOrFail($request->pr_document_id);
                $prDocument->update($input);
            }
        });  //end transaction


        return response()->json(['status' => 'OK', 'message' => "Document Successfully Saved"]);
    }


    // public function store(DocumentStore $request)
    // {

    //     $input = $request->all();


    //     if ($request->filled('document_date')) {
    //         $input['document_date'] = \Carbon\Carbon::parse($request->document_date)->format('Y-m-d');
    //     }

    //     DB::transaction(function () use ($request, $input) {

    //         $extension = request()->document->getClientOriginalExtension();

    //         $fileName = strtolower(preg_replace('/[^a-zA-Z0-9_ -]/s', '', str_replace(" ", "_", $input['description']))) . '-' . time() . '.' . $extension;
    //         $folderName = "project/" . session('pr_detail_id') . "/";
    //         //store file
    //         $request->file('document')->storeAs('public/' . $folderName, $fileName);

    //         $file_path = storage_path('app/public/' . $folderName . $fileName);

    //         $input['content'] = '';

    //         if (($extension == 'doc') || ($extension == 'docx')) {
    //             $text = new DocxConversion($file_path);
    //             $input['content'] = mb_strtolower($text->convertToText());
    //         } else if ($extension == 'pdf') {
    //             $reader = new \Asika\Pdf2text;
    //             $input['content'] = mb_strtolower($reader->decode($file_path));
    //         }


    //         $input['file_name'] = $fileName;
    //         $input['size'] = $request->file('document')->getSize();
    //         $input['path'] = $folderName;
    //         $input['extension'] = $extension;
    //         $input['pr_detail_id'] = session('pr_detail_id');

    //         $prDocument = PrDocument::create($input);
    //         $input['pr_document_id'] = $prDocument->id;
    //         // $test = $input['content'];
    //         //$test = strlen($input['content']);
    //         if (strlen($input['content']) > 50 && strlen($input['content']) < 16777200) {

    //             PrDocumentContent::create($input);
    //         }

    //         //add document path into employee record    
    //         if ($request->filled("hr_employee_id.0")) {
    //             if (strlen($input['content']) > 65000) {
    //                 $input['content'] = '';
    //             }
    //             for ($i = 0; $i < count($request->input('hr_employee_id')); $i++) {
    //                 $input['hr_employee_id'] = $request->input("hr_employee_id.$i");
    //                 $input['pr_document_id'] = $prDocument->id;
    //                 $hrDocumentation = HrDocumentation::create($input);
    //                 $input['hr_documentation_id'] = $hrDocumentation->id;

    //                 HrDocumentationProject::create($input);
    //             }
    //         }
    //     });  //end transaction

    //     return response()->json(['status' => 'OK', 'message' => "Data Successfully Saved"]);
    // }


    public function edit($id)
    {
        $prDocument = PrDocument::find($id);
        return response()->json($prDocument);
    }


    // public function edit(Request $request, $id)
    // {
    //     //For security checking
    //     session()->put('pr_document_edit_id', $id);

    //     $employees = HrEmployee::where('hr_status_id', 1)->get();
    //     $hrDocumentationIds = HrDocumentationProject::where('pr_document_id', $id)->get()->pluck('hr_documentation_id')->toArray();

    //     $employeeDocuments = HrDocumentation::wherein('id', $hrDocumentationIds)->get()->pluck('hr_employee_id')->toArray();


    //     $prFolderNames = PrFolderName::all();

    //     $data = PrDocument::find($id);

    //     if ($request->ajax()) {
    //         return view('project.document.edit', compact('prFolderNames', 'data', 'employees', 'employeeDocuments'));
    //     } else {
    //         return back()->withError('Please contact to administrator, SSE_JS');
    //     }
    // }


    // public function update(Request $request, $id)
    // {
    //     if ($id != session('pr_document_edit_id')) {
    //         return response()->json(['status' => 'Not OK', 'message' => "Security Breach. No Data Change "]);
    //     }

    //     $input = $request->all();

    //     if ($request->filled('document_date')) {
    //         $input['document_date'] = \Carbon\Carbon::parse($request->document_date)->format('Y-m-d');
    //     }



    //     $prDocument = PrDocument::findOrFail($id);

    //     DB::transaction(function () use ($request, $input, $id, $prDocument) {
    //         //If document attached then first delete existing file and then new file save
    //         if ($request->hasFile('document')) {

    //             //Delete Existing Document
    //             $path = public_path('storage/' . $prDocument->path . $prDocument->file_name);
    //             if (File::exists($path)) {
    //                 File::delete($path);
    //             }

    //             //Now save new Document
    //             $extension = request()->document->getClientOriginalExtension();
    //             $fileName = strtolower(preg_replace('/[^a-zA-Z0-9_ -]/s', '', str_replace(" ", "_", $input['description']))) . '-' . time() . '.' . $extension;
    //             $folderName = "project/" . session('pr_detail_id') . "/";
    //             //store file
    //             $request->file('document')->storeAs('public/' . $folderName, $fileName);

    //             $file_path = storage_path('app/public/' . $folderName . $fileName);

    //             $input['content'] = '';

    //             if (($extension == 'doc') || ($extension == 'docx')) {
    //                 $text = new DocxConversion($file_path);
    //                 $input['content'] = mb_strtolower($text->convertToText());
    //             } else if ($extension == 'pdf') {
    //                 $reader = new \Asika\Pdf2text;
    //                 $input['content'] = mb_strtolower($reader->decode($file_path));
    //             }

    //             $input['file_name'] = $fileName;
    //             $input['size'] = $request->file('document')->getSize();
    //             $input['path'] = $folderName;
    //             $input['extension'] = $extension;
    //             $input['pr_detail_id'] = session('pr_detail_id');


    //             //update project document content  
    //             if (strlen($input['content']) > 50 && strlen($input['content']) < 16777200) {
    //                 PrDocumentContent::updateOrCreate(
    //                     ['pr_document_id' => $id],       //It is find and update 
    //                     $input
    //                 );
    //             }
    //         }

    //         // Now update remaining inputs in pr_document
    //         $prDocument->update($input);




    //         //if any employee removing from input than delete also in Hr Documentation
    //         $hrDocumentationIds = HrDocumentationProject::where('pr_document_id', $id)->get()->pluck('hr_documentation_id')->toArray();

    //         $existingHrEmployeeIds = HrDocumentation::wherein('id', $hrDocumentationIds)->get()->pluck('hr_employee_id')->toArray();
    //         $existingHrEmployeeIds = collect($existingHrEmployeeIds);


    //         if ($request->filled("hr_employee_id.0")) {
    //             $employeeIds = $input['hr_employee_id'];

    //             foreach ($employeeIds as $employeeId) {
    //                 $key = $existingHrEmployeeIds->search($employeeId);
    //                 $input['hr_employee_id'] = $employeeId;

    //                 //(if) update (else) create (afer condition) remainig delete 
    //                 if ($existingHrEmployeeIds->contains($employeeId)) {
    //                     HrDocumentation::wherein('id', $hrDocumentationIds)->where('hr_employee_id', $employeeId)->first()->update($input);
    //                     $existingHrEmployeeIds->pull($key);
    //                 } else {

    //                     $input['file_name'] = $prDocument->file_name;
    //                     $input['size'] = $prDocument->size;
    //                     $input['path'] = $prDocument->path;
    //                     $input['extension'] = $prDocument->extension;
    //                     $input['pr_document_id'] = $id;
    //                     $input['content'] = $prDocument->prDocumentContent->content;
    //                     if (strlen($prDocument->prDocumentContent->content) > 65000) {
    //                         $input['content'] = '';
    //                     }
    //                     $hrDocumentation = HrDocumentation::create($input);
    //                     $input['hr_documentation_id'] = $hrDocumentation->id;
    //                     HrDocumentationProject::create($input);
    //                 }
    //             }

    //             //remaining delete here
    //             HrDocumentation::wherein('id', $hrDocumentationIds)->wherein('hr_employee_id', $existingHrEmployeeIds)->delete();
    //         } else {
    //             // hr_employee_id not fill then above line not execute so following line execute for delete HrDocuments
    //             HrDocumentation::wherein('id', $hrDocumentationIds)->delete();
    //         }
    //     });  //end transaction

    //     return response()->json(['status' => 'OK', 'message' => "Data Successfully Saved"]);
    // }


    public function destroy($id)
    {

        DB::transaction(function () use ($id) {

            $prDocument = PrDocument::findOrFail($id);

            $path = public_path('storage/' . $prDocument->path . $prDocument->file_name);

            $hrDocumentationIds = HrDocumentationProject::where('pr_document_id', $id)->get()->pluck('hr_documentation_id')->toArray();
            HrDocumentation::wherein('id', $hrDocumentationIds)->delete();
            $prDocument->forceDelete();

            if (File::exists($path)) {
                File::delete($path);
            }
        });  //end transaction


        return response()->json(['status' => 'OK', 'message' => "Data Successfully Deleted"]);
    }



    public function refreshTable()
    {
        $documentIds = PrDocument::where('pr_detail_id', session('pr_detail_id'))->get();
        return view('project.document.list', compact('documentIds'));
    }
}
