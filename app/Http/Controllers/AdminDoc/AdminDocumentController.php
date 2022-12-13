<?php

namespace App\Http\Controllers\AdminDoc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminDoc\AdminDocument;
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
                    return $data->file_name . $data->path;
                    // '<a><img src="{{asset('Massets/images/copyLink.png')}}" width=30 /></a>'

                })
                ->addColumn('edit', function ($data) {
                    $button = '<a class="btn btn-success btn-sm" href="' . route('adminDocument.edit', $data->id) . '"  title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>';
                    return $button;
                })
                ->addColumn('delete', function ($data) {
                    $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteDocument">Delete</a>';
                    return $button;
                })
                ->rawColumns(['edit', 'delete'])
                ->make(true);
        }
        return view('adminDocument.list');
    }
}
