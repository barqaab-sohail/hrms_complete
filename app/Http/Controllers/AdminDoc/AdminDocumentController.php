<?php

namespace App\Http\Controllers\AdminDoc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminDoc\AdminDocument;
use App\Models\AdminDoc\AdminDocumentContent;
use App\Helper\DocxConversion;
use DB;
use DataTables;

class AdminDocumentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = AdminDocument::orderBy('id', 'desc')->get();

            return DataTables::of($data)
                ->addColumn('link', function ($data) {
                    $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-link="' . url('/storage/' . $data->path . $data->file_name) . '" data-mis-user="false" data-original-title="edit mis rights" class="btn btn-primary btn-sm copyLink">Copy Link</a>';
                    return $button;
                })
                ->addColumn('view', function ($data) {
                    if ($data->extension === 'pdf') {
                        $pdf = '<img id="ViewPDF" src="' . asset('Massets/images/document.png') . '" href="' . asset(isset($data->file_name) ? 'storage/' . $data->path . $data->file_name : 'Massets/images/document.png') . '" width=30 />';
                        return $pdf;
                    } else if ($data->extension === 'jpeg' || $data->extension === 'png' || $data->extension === 'jpg') {
                        $image = '<img src="' . url(isset($data->file_name) ? '/storage/' . $data->path . $data->file_name : 'Massets/images/document.png') . '" class="img-round picture-container picture-src"  id="ViewIMG' . $data->id . '" width=50>';
                        return $image;
                    } else {
                        $doc = '<a href="' . asset('storage/' . $data->path . $data->file_name) . '" width=30><i class="fa fa-download" aria-hidden="true"></i>Download</a>';
                        return $doc;
                    }
                })
                ->addColumn('edit', function ($data) {
                    $button =  '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Edit" class="btn btn-success btn-sm editDocument">Edit</a>';
                    //'<a class="btn btn-success btn-sm editDocument" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Edit"><i class="fas fa-pencil-alt text-white"></i></a>';
                    return $button;
                })
                ->addColumn('delete', function ($data) {

                    $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteDocument">Delete</a>';
                    return $button;
                })
                ->rawColumns(['edit', 'delete', 'link', 'view'])
                ->make(true);
        }
        return view('adminDocument.list');
    }
    public function store(Request $request)
    {

        $input = $request->all();


        if ($request->filled('document_date')) {
            $input['document_date'] = \Carbon\Carbon::parse($request->document_date)->format('Y-m-d');
        }

        DB::transaction(function () use ($request, $input) {
            $input['content'] = '';

            if ($request->hasFile('document')) {
                $extension = request()->document->getClientOriginalExtension();
                $fileName = strtolower(preg_replace('/[^a-zA-Z0-9_ -]/s', '', $input['description'])) . '-' . time() . '.' . $extension;
                $folderName = "adminDocument/";
                //store file
                $request->file('document')->storeAs('public/' . $folderName, $fileName);

                $file_path = storage_path('app/public/' . $folderName . $fileName);

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
                $input['pr_detail_id'] = session('pr_detail_id');
            }
            $adminDocument = AdminDocument::updateOrCreate(['id' => $input['document_id']], $input);

            $input['admin_document_id'] = $adminDocument->id;

            if (strlen($input['content']) > 50 && strlen($input['content']) < 16777200) {

                AdminDocumentContent::updateOrCreate(['admin_document_id' => $adminDocument->id], $input);
            } else if ($request->hasFile('document') && $input['content'] < 50) {
                AdminDocumentContent::where('admin_document_id', $adminDocument->id)->delete();
            }
        });  //end transaction

        return response()->json(['status' => 'OK', 'message' => "Data Successfully Saved"]);
    }

    public function edit($id)
    {
        $adminDocument = AdminDocument::find($id);
        return response()->json($adminDocument);
    }

    public function destroy($id)
    {

        DB::transaction(function () use ($id) {
            AdminDocument::find($id)->delete();
        }); // end transcation

        return response()->json(['success' => "data  delete successfully."]);
    }

    public function reference(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');
            $data = DB::table('admin_documents')
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
}
