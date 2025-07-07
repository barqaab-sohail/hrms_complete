<?php

namespace App\Http\Controllers\Folder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Folder\FolderDocument;
use App\Models\Folder\Folder;
use App\Http\Requests\Folder\FolderDocumentStore;
use DataTables;
use DB;


class FolderDocumentsController extends Controller
{
    public function show($id)
    {
        $folder = Folder::find($id);
        return view('folder.document.create', compact('folder'));
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            $data = FolderDocument::where('folder_id', $request->folderId)
                ->orderBy('document_date', 'desc') // Add this line to sort by document_date in descending order
                ->latest()
                ->get();


            return  DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('description', function ($row) {
                    return ucwords(strtolower($row->description)); // Convert to Title Case
                })
                ->editColumn('document_date', function ($row) {
                    if ($row->document_date) {
                        return \Carbon\Carbon::parse($row->document_date)->format('M d, Y');
                    } else {
                        return '';
                    }
                })
                ->addColumn('Edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editDocument">Edit</a>';

                    return $btn;
                })
                ->addColumn('copy_link', function ($row) {
                    return '<a class="copyLink" link="' . $row->tiny_url . '" style="cursor: auto;" title="Click for Copy Tiny URL"><img src="https://hrms.barqaab.pk/Massets/images/copyLink.png" width="30"></a>';
                })
                ->addColumn('Delete', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteDocument">Delete</a>';


                    return $btn;
                })
                ->addColumn('document', function ($row) {

                    $url = asset('storage/' . $row->path . $row->file_name);
                    $pdfMono = asset('Massets/images/document.png');
                    if ($row->extension == 'pdf') {
                        return '<img  id="ViewPDF" src="' . $pdfMono . '" href="' . $url . '" width=30/>';
                    } else {
                        return '<img  id="ViewIMG" src="' . $url . '" width=30/>';
                    }
                })
                ->rawColumns(['Edit', 'Delete', 'document', 'copy_link'])
                ->make(true);
        }

        $view =  view('folder.document.create')->render();
        return response()->json($view);
    }

    public function store(FolderDocumentStore $request)
    {
        $folder = Folder::find($request->folder_id);

        $input = $request->all();

        if ($request->filled('document_date')) {
            $input['document_date'] = \Carbon\Carbon::parse($request->document_date)->format('Y-m-d');
        }

        DB::transaction(function () use ($input, $request, $folder) {

            $attachment['document_date'] = $input['document_date'];
            $attachment['reference_no'] = $input['reference_no'];
            //add image
            if ($request->hasFile('document')) {
                $extension = request()->document->getClientOriginalExtension();
                $fileName = time() . '.' . $extension;
                $folderName = "folder/" . $folder->id . "/";
                //store file
                $request->file('document')->storeAs('public/' . $folderName, $fileName);

                $file_path = storage_path('app/public/' . $folderName . $fileName);
                $attachment['file_name'] = $fileName;
                $attachment['size'] = $request->file('document')->getSize();
                $attachment['path'] = $folderName;
                $attachment['extension'] = $extension;

                $attachment['folder_id'] =  $input['folder_id'];
                $attachment['description'] = $input['description'];


                $folderDocument = FolderDocument::where('id', request()->folder_document_id)->first();

                if ($folderDocument) {
                    $oldDocumentPath =  $folderDocument->path . $folderDocument->file_name;
                    FolderDocument::findOrFail($folderDocument->id)->update($attachment);

                    if (File::exists(public_path('storage/' . $oldDocumentPath))) {
                        File::delete(public_path('storage/' . $oldDocumentPath));
                    }
                } else {
                    FolderDocument::create($attachment);
                }
            } else {
                // update existing document without file upload
                $updateData = [
                    'document_date' => $input['document_date'],
                    'reference_no' => $input['reference_no'],
                    'description' => $input['description'],
                ];
                FolderDocument::findOrFail($input['folder_document_id'])->update($updateData);
            }
        }); // end transcation     

        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {
        $document = FolderDocument::find($id);
        return response()->json($document);
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $folderDocument = FolderDocument::where('id', $id)->first();
            $documentPath =  $folderDocument->path . $folderDocument->file_name;
            $folderDocument->delete();

            if (File::exists(public_path('storage/' . $documentPath))) {
                File::delete(public_path('storage/' . $documentPath));
            }
        }); // end transcation 
        return response()->json(['success' => 'data  delete successfully.']);
    }
}
