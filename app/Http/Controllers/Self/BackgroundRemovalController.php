<?php

namespace App\Http\Controllers\Self;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackgroundRemovalController extends Controller
{
    public function showForm()
    {
        return view('self.remove_bg.create');
    }

    public function processImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);


        try {
            $uploadedPath = $request->file('image')->store('temp');
            $fullInputPath = storage_path('app/' . $uploadedPath);
            $outputFilename = 'bg_removed_' . time() . '.png';
            $fullOutputPath = storage_path('app/public/' . $outputFilename);

            $pythonScriptPath = app_path('Python/remove_bg.py');

            // Increase timeout to 300 seconds (5 minutes)
            $process = new Process(
                ['python', $pythonScriptPath, $fullInputPath, $fullOutputPath],
                null,
                null,
                null,
                300 // Timeout in seconds
            );

            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            Storage::delete($uploadedPath);
            return Storage::disk('public')->download($outputFilename);
        } catch (\Exception $e) {
            Storage::delete($uploadedPath ?? '');
            return back()->with('error', 'Background removal failed: ' . $e->getMessage());
        }
    }
}
