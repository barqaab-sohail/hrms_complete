<?php

namespace App\Http\Controllers\Self;

use App\Http\Controllers\Controller;
use App\Models\Self\PersonalDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Self\PersonalDocumentStore;
use DataTables;
use DB;


class PersonalDocumentsController extends Controller
{

    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = PersonalDocument::where('user_id', Auth::id())->orderByRaw('ISNULL(document_date), document_date desc')->get();
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

                ->rawColumns(['document', 'copy_link', 'Edit', 'Delete'])
                ->make(true);
        }
    }




    public function create(Request $request)
    {

        return view('self.document.create');
    }


    public function store(PersonalDocumentStore $request)
    {

        $input = $request->only('description', 'document', 'personal_document_id');

        if ($request->filled('document_date')) {
            $input['document_date'] = \Carbon\Carbon::parse($request->document_date)->format('Y-m-d');
        }

        DB::transaction(function () use ($request, $input) {

            // Only document Avaiable
            if ($request->hasFile('document')) {
                $extension = request()->document->getClientOriginalExtension();

                $fileName = strtolower(preg_replace('/[^a-zA-Z0-9_ -]/s', '', str_replace(" ", "_", $input['description']))) . '-' . time() . '.' . $extension;
                $folderName = "personal/" . Auth::id() . "/";
                //store file
                $request->file('document')->storeAs('public/' . $folderName, $fileName);

                $file_path = storage_path('app/public/' . $folderName . $fileName);


                $input['file_name'] = $fileName;
                $input['size'] = $request->file('document')->getSize();
                $input['path'] = $folderName;
                $input['extension'] = $extension;
                $input['user_id'] = Auth::id();

                //check create new data or update data
                if ($request->personal_document_id) {
                    //update record
                    $personalDocument = PersonalDocument::findOrFail($request->personal_document_id);
                    $path = public_path('storage/' . $personalDocument->path . $personalDocument->file_name);
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                    $personalDocument->update($input);
                    $input['personal_document_id'] = $request->personal_document_id;
                } else {

                    //create record
                    $personalDocument = PersonalDocument::create($input);
                    $input['personal_document_id'] = $personalDocument->id;
                }
            } else {

                $personalDocument = PersonalDocument::findOrFail($request->personal_document_id);
                $personalDocument->update($input);
            }
        });  //end transaction


        return response()->json(['status' => 'OK', 'message' => "Document Successfully Saved"]);
    }





    public function edit($id)
    {
        $personalDocument = PersonalDocument::find($id);
        return response()->json($personalDocument);
    }





    public function destroy($id)
    {

        DB::transaction(function () use ($id) {

            $personalDocument = PersonalDocument::findOrFail($id);

            $path = public_path('storage/' . $personalDocument->path . $personalDocument->file_name);

            $personalDocument->forceDelete();

            if (File::exists($path)) {
                File::delete($path);
            }
        });  //end transaction


        return response()->json(['status' => 'OK', 'message' => "Data Successfully Deleted"]);
    }
}
