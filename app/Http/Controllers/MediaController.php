<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function create()

    {

        return view('media');

    }

    public function uploadMedia(Request $request)

    {

        $file = $request->file('file');

        $chunkNumber = $request->input('resumableChunkNumber');

        $totalChunks = $request->input('resumableTotalChunks');

        $fileName = $request->input('resumableFilename');

        $filePath = storage_path('app/uploads/' . $fileName . '.part');

        // Move the uploaded chunk to the temporary directory

        $file->move(storage_path('app/uploads'), $fileName . '.part' . $chunkNumber);

        if ($chunkNumber == $totalChunks) {

            $this->mergeChunks($fileName, $totalChunks);

        }

        return response()->json(['message' => 'Chunk uploaded successfully']);

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
}