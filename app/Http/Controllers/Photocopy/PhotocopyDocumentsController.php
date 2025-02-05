<?php

namespace App\Http\Controllers\Photocopy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Photocopy\PhotocopyDocument;
use App\Models\Photocopy\Photocopy;
use App\Http\Requests\Photocopy\PhotocopyDocumentStore;
use DataTables;
use DB;

class PhotocopyDocumentsController extends Controller
{

    public function show($id)
    {
        $photocopy = Photocopy::find($id);
        return view('photocopy.document.create', compact('photocopy'));
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            $data = PhotocopyDocument::where('photocopy_id', $request->photocopyId)->latest()->get();

            return  DataTables::of($data)
                ->addIndexColumn()
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
                ->rawColumns(['Edit', 'Delete', 'document'])
                ->make(true);
        }

        $view =  view('photocopy.document.create')->render();
        return response()->json($view);
    }

    public function store(PhotocopyDocumentStore $request)
    {
        $photocopy = Photocopy::find($request->photocopy_id);

        $input = $request->all();

        if ($request->filled('document_date')) {
            $input['document_date'] = \Carbon\Carbon::parse($request->document_date)->format('Y-m-d');
        }

        DB::transaction(function () use ($input, $request, $photocopy) {

            $attachment['document_date'] = $input['document_date'];
            //add image
            if ($request->hasFile('document')) {
                $extension = request()->document->getClientOriginalExtension();
                $fileName = time() . '.' . $extension;
                $folderName = "photocopy/" . $photocopy->id . "/";
                //store file
                $request->file('document')->storeAs('public/' . $folderName, $fileName);

                $file_path = storage_path('app/public/' . $folderName . $fileName);
                $attachment['file_name'] = $fileName;
                $attachment['size'] = $request->file('document')->getSize();
                $attachment['path'] = $folderName;
                $attachment['extension'] = $extension;

                $attachment['photocopy_id'] =  $input['photocopy_id'];
                $attachment['description'] = $input['description'];
                

                $photocopyDocument = PhotocopyDocument::where('id', request()->photocopy_document_id)->first();

                if ($photocopyDocument) {
                    $oldDocumentPath =  $photocopyDocument->path . $photocopyDocument->file_name;
                    PhotocopyDocument::findOrFail($photocopyDocument->id)->update($attachment);

                    if (File::exists(public_path('storage/' . $oldDocumentPath))) {
                        File::delete(public_path('storage/' . $oldDocumentPath));
                    }
                } else {
                    PhotocopyDocument::create($attachment);
                }
            }

            if ($input['photocopy_document_id']) {

                PhotocopyDocument::findOrFail($input['photocopy_document_id'])->update($input);
            }
        }); // end transcation     

        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {
        $document = PhotocopyDocument::find($id);
        return response()->json($document);
    }

    public function destroy($id)
    {
        $documentImage = PhotocopyDocument::where('id', $id)->first();


        DB::transaction(function () use ($id) {
            $photocopyDocument = PhotocopyDocument::where('id', $id)->first();
            $documentPath =  $photocopyDocument->path . $photocopyDocument->file_name;
            $photocopyDocument->delete();

            if (File::exists(public_path('storage/' . $documentPath))) {
                File::delete(public_path('storage/' . $documentPath));
            }
        }); // end transcation 
        return response()->json(['success' => 'data  delete successfully.']);
    }
}
