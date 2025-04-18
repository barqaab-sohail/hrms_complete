<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Hr\HrEmployee;

class RunEmployeeFaceDetection extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qc:employee-faces';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detect face in employee pictures using Python and OpenCV';

    /**
     * Execute the console command.
     */
    public function handle()
    {


        $employees = HrEmployee::with('picture')->where('id', 17)->get();

        $lines = []; // Collect lines to write in a .txt file

        foreach ($employees as $employee) {
            $fullPath = $employee->picture;
            $relativePath = str_replace(url('storage') . '/', '', $fullPath);

            if (!$fullPath || !Storage::disk('public')->exists($relativePath)) {
                $employee->has_face = false;
                $employee->face_qc_comments = 'Picture not found';
                $employee->face_qc_checked_at = now();
                //  $employee->save();

                // Not included in file since not "face detection error" or "no face detected"
                continue;
            }

            $this->info("Checking: {$employee->full_name}");

            $output = null;
            $status = null;

            exec("python face_detect.py " . escapeshellarg($fullPath), $output, $status);

            if ($status !== 0 || empty($output)) {
                $employee->has_face = false;
                $employee->face_qc_comments = 'Face detection error';
            } else {
                $result = trim($output[0]);
                $employee->has_face = $result === 'true';
                $employee->face_qc_comments = $result === 'true' ? null : 'No face detected';
            }

            $employee->face_qc_checked_at = now();
            // $employee->save();

            // Collect if condition is matched
            if (in_array($employee->face_qc_comments, ['Face detection error', 'No face detected'])) {
                $lines[] = "{$employee->full_name} ({$employee->employee_no}) - {$employee->face_qc_comments}";
            }
        }

        // Save to a txt file
        if (!empty($lines)) {
            $fileName = 'face_detection_issues_' . now()->format('Y_m_d_His') . '.txt';
            $filePath = storage_path("app/{$fileName}");
            file_put_contents($filePath, implode(PHP_EOL, $lines));

            $this->info("File saved: {$filePath}");
        } else {
            $this->info("No face detection issues found.");
        }
    }
}
