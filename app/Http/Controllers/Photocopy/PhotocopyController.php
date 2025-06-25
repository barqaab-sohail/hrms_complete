<?php

namespace App\Http\Controllers\Photocopy;

use DB;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Photocopy\Photocopy;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;


class PhotocopyController extends Controller
{


    public function create(Request $request)
    {

        // Return the view for regular requests
        return view('photocopy.create')->render();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Photocopy::all();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editPhotocopy">Edit</a>';

                    return $btn;
                })
                ->addColumn('Delete', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deletePhotocopy">Delete</a>';


                    return $btn;
                })
                ->rawColumns(['Edit', 'Delete'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => "required|max:255|" . Rule::unique('photocopies')->ignore($request['photocopy_id']),
            'type' => 'required'
        ]);



        DB::transaction(function () use ($request) {
            Photocopy::updateOrCreate(
                ['id' => $request['photocopy_id']],
                [
                    'name' => $request['name'],
                    'type' => $request['type'],
                ]
            );
        }); // end transcation      
        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {
        $photocopy = Photocopy::find($id);
        return response()->json($photocopy);
    }


    public function destroy(Request $request, $id)
    {

        DB::transaction(function () use ($id) {
            Photocopy::find($id)->delete();
        }); // end transcation 
        return response()->json(['message' => 'data  delete successfully.']);
    }
}
