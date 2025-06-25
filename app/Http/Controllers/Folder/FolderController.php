<?php

namespace App\Http\Controllers\Folder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Folder\Folder;
use Illuminate\Validation\Rule;
use DB;
use DataTables;

class FolderController extends Controller
{

    public function list()
    {
        $folders = Folder::all();
        return view('folder.list', compact('folders'));
    }

    public function create(Request $request)
    {

        return  view('folder.create');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Folder::all();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editFolder">Edit</a>';

                    return $btn;
                })
                ->addColumn('Delete', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteFolder">Delete</a>';


                    return $btn;
                })
                ->rawColumns(['Edit', 'Delete'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => "required|max:255|" . Rule::unique('folders')->ignore($request['folder_id']),
        ]);



        DB::transaction(function () use ($request) {
            Folder::updateOrCreate(
                ['id' => $request['folder_id']],
                [
                    'name' => $request['name'],
                ]
            );
        }); // end transcation      
        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {
        $photocopy = Folder::find($id);
        return response()->json($photocopy);
    }


    public function destroy(Request $request, $id)
    {

        DB::transaction(function () use ($id) {
            Folder::find($id)->delete();
        }); // end transcation 
        return response()->json(['message' => 'data  delete successfully.']);
    }
}
