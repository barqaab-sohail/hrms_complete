<?php

namespace App\Http\Controllers\Photocopy;

use DB;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Photocopy\Photocopy;
use App\Http\Controllers\Controller;
use App\Models\Photocopy\PhotocopyRecord;

class PhotocopyRecordController extends Controller
{

    public function list()
    {

        $photocopies = Photocopy::all();

        return view('photocopy.photocopy_record.list', compact('photocopies'));
    }

    // public function edit($id){
    //     $photocopy = Photocopy::find($id);
    //     $photocopyRecords = PhotocopyRecord::where('photocopy_id',$photocopy->id)->get();
    //     return view ('photocopy.phtocopy_record.detail', compact('photocopy','photocopyRecords'));
    // }


    public function show(Request $request, $id)
    {

        $photocopy = Photocopy::find($id);
        $photocopyRecords = PhotocopyRecord::where('photocopy_id', $photocopy->id)->orderBy('date', 'DESC')->get();

        if ($request->ajax()) {

            return DataTables::of($photocopyRecords)
                ->addIndexColumn()
                ->addColumn('copies', function ($row) {

                    $perviousData = PhotocopyRecord::where('date', '<', $row->date)->where('photocopy_id', $row->photocopy_id)->orderBy('date', 'DESC')->first();
                    if ($perviousData) {
                        $blackCopies =  $row->reading - $perviousData->reading;
                        $colorCopies = $row->color_reading - $perviousData->color_reading;
                        return $blackCopies + $colorCopies;
                    } else {
                        return 0;
                    }
                })
                ->addColumn('Edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editRecord">Edit</a>';

                    return $btn;
                })
                ->addColumn('Delete', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteRecord">Delete</a>';


                    return $btn;
                })
                ->editColumn('date', function ($row) {

                    return \Carbon\Carbon::parse($row->date)->format('M d, Y');
                })
                ->rawColumns(['Edit', 'Delete'])
                ->make(true);
        }

        return view('photocopy.photocopy_record.detail', compact('photocopy', 'photocopyRecords'));
    }

    public function store(Request $request)
    {

        if ($request->filled('date')) {
            $request['date'] = \Carbon\Carbon::parse($request->date)->format('Y-m-d');
        }

        $maxRecord = PhotocopyRecord::where('photocopy_id', $request['photocopy_id'])->where('date', '<', $request['date'])->max('reading');
        if (!$maxRecord) {
            $maxRecord = 0;
        }
        $maxColorRecord = PhotocopyRecord::where('photocopy_id', $request['photocopy_id'])->where('date', '<', $request['date'])->max('color_reading');
        if (!$maxColorRecord) {
            $maxColorRecord = 0;
        }
        $colorReading = 'nullable';
        $photocopy = Photocopy::find($request['photocopy_id']);
        if ($photocopy->type == "Color") {
            $colorReading = 'required';
        }
        $validated = $request->validate([
            'date' => "required|date|" . Rule::unique('photocopy_records')->where('photocopy_id', $request['photocopy_id'])->ignore($request['record_id']),
            'reading' => "required|gte:$maxRecord",
            'color_reading' => "$colorReading|gte:$maxColorRecord",
            'photocopy_id' => 'required',
            'remarks' => 'nullable|max:191'
        ]);




        DB::transaction(function () use ($request) {
            PhotocopyRecord::updateOrCreate(
                ['id' => $request['record_id']],
                [
                    'date' => $request['date'],
                    'reading' => $request['reading'],
                    'color_reading' => $request['color_reading'],
                    'remarks' => $request['remarks'],
                    'photocopy_id' => $request['photocopy_id'],
                ]
            );
        }); // end transcation      
        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {
        $photocopyRecord = PhotocopyRecord::find($id);
        return response()->json($photocopyRecord);
    }


    public function destroy($id)
    {

        DB::transaction(function () use ($id) {
            PhotocopyRecord::find($id)->delete();
        }); // end transcation 
        return response()->json(['message' => 'data  delete successfully.']);
    }
}
