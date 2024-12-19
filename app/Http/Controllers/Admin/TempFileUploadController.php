<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrAllowanceName;
use DataTables;
use DB;

class TempFileUploadController extends Controller
{
    
    public function create(Request $request)
    {
        if($request->ajax()){
	    	$data = HrAllowanceName::all();
	    	return DataTables::of($data)	
	            
	            ->addColumn('Delete', function($data){
	                  
	                $button = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$data->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteAllowanceName">Delete</a>';
	                return $button;
	            })
	            ->rawColumns(['Edit','Delete'])
	            ->make(true);
	    }
        
    	return view ('admin.tempFileUpload.list');
       

       
        // return view('media');

    }

    public function uploadMedia(Request $request)

    {

       
        $file = $request->file('file');

        $chunkNumber = $request->input('resumableChunkNumber');

        $totalChunks = $request->input('resumableTotalChunks');

        $fileName = str_replace(' ', '', $request->input('resumableFilename'));

        $filePath = storage_path('app/uploads/' . $fileName . '.part');

        // Move the uploaded chunk to the temporary directory

        $file->move(storage_path('app/uploads'), $fileName . '.part' . $chunkNumber);

        if ($chunkNumber == $totalChunks) {

            $this->mergeChunks($fileName, $totalChunks);

        }

        return response()->json(['message' =>  'File Sucessfully Uploaded']);

    }

    private function mergeChunks($fileName, $totalChunks)

    {

        $filePath = storage_path('app/uploads/' . $fileName);

        $output = fopen($filePath, 'wb');

        for ($i = 1; $i <= $totalChunks; $i++) {

            $chunkPath = storage_path('app/uploads/' . $fileName . '.part' . $i);

            $chunkFile = fopen($chunkPath, 'rb');

            stream_copy_to_stream($chunkFile, $output);

            fclose($chunkFile);

            unlink($chunkPath);

        }

        fclose($output);

    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {  
            HrAllowanceName::findOrFail($id)->delete();   
        }); // end transcation

        return response()->json(['success'=>'data  delete successfully.']);
   
    }
}
