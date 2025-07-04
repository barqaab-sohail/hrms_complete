<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Hr\HrDocumentation;
use Illuminate\Support\Facades\Storage;

class ExperienceLetterController extends Controller
{
    public function create()
    {
        $employees = HrEmployee::orderBy('first_name')->get();
        return view('hr.experience_letter.create', compact('employees'));
    }

    public function preview(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:hr_employees,id',
            'project' => 'nullable|string|max:255'
        ]);

        $employee = HrEmployee::findOrFail($request->employee_id);

        // Determine letter type based on hr_status_id
        $isCurrentEmployee = $employee->hr_status_id == "Active";

        // Use manual project if provided, otherwise get from employee
        $project = $request->filled('project') ? $request->project : ($employee->project ?? 'N/A');

        $data = [
            'employee' => $employee,
            'designation' => $employee->designation,
            'date' => now()->format('F j, Y'),
            'signatory' => 'CH. ATIQ A. HUMAYUN',
            'signatory_position' => 'Deputy Manager (HR & Admin)',
            'letterhead_url' => url('letterhead/barqaab_letterhead.jpg'),
            'is_current_employee' => $isCurrentEmployee,
            'project' => $project,
            'joining_date' => $employee->joining_date,
            'leaving_date' => !$isCurrentEmployee && $employee->last_working_date
                ? $employee->last_working_date
                : null
        ];

        return view('hr.experience_letter.preview', $data);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:hr_employees,id',
            'project' => 'nullable|string|max:255'
        ]);

        $employee = HrEmployee::findOrFail($request->employee_id);
        $isCurrentEmployee = $employee->hr_status_id == "Active";
        $project = $request->filled('project') ? $request->project : ($employee->project ?? 'N/A');

        // Generate filename and path
        $fileName = "experience_letter_" . ($isCurrentEmployee ? 'current' : 'previous') .
            "_{$employee->first_name}_{$employee->last_name}_" . time() . '.pdf';
        $downloadFileName = "experience_letter_" . ($isCurrentEmployee ? 'current' : 'previous') .
            "_{$employee->first_name}_{$employee->last_name}.pdf";
        $employeeName = str_replace(' ', '_', strtolower($employee->full_name));
        $folderName = "hr/documentation/" . $employee->id . '-' . $employeeName . "/";
        $fullPath = $folderName . $fileName;

        $data = [
            'employee' => $employee,
            'designation' => $employee->designation,
            'date' => now()->format('F j, Y'),
            'signatory' => 'CH. ATIQ A. HUMAYUN',
            'signatory_position' => 'Deputy Manager (HR & Admin)',
            'letterhead_url' => url('letterhead/barqaab_letterhead.jpg'),
            'stamp' => asset('letterhead/stamp.png'),
            'sign' => asset('letterhead/sign.png'),
            'is_current_employee' => $isCurrentEmployee,
            'project' => $project,
            'joining_date' => $employee->joining_date,
            'leaving_date' => !$isCurrentEmployee && $employee->last_working_date
                ? $employee->last_working_date
                : null,
            'path' => $fullPath
        ];

        $pdfContent = Pdf::loadView('hr.experience_letter.pdf', $data)
            ->setPaper('A4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('dpi', 300)
            ->setOption('defaultFont', 'DejaVu Sans')
            ->output();

        Storage::disk('public')->put($fullPath, $pdfContent);

        $documentation = HrDocumentation::create([
            'hr_employee_id' => $employee->id,
            'description' => 'Experience Letter (' . ($isCurrentEmployee ? 'current' : 'previous') .
                ') for ' . $employee->full_name . "-" . now(),
            'document_date' => now(),
            'file_name' => $fileName,
            'path' => $folderName,
            'size' => Storage::disk('public')->size($fullPath),
            'extension' => 'pdf',
            'content' => null
        ]);

        return response()->streamDownload(
            function () use ($pdfContent) {
                echo $pdfContent;
            },
            $downloadFileName,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $downloadFileName . '"',
            ]
        );
    }

    public function generateWithoutLetterhead(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:hr_employees,id',
            'project' => 'nullable|string|max:255'
        ]);

        $employee = HrEmployee::findOrFail($request->employee_id);
        $isCurrentEmployee = $employee->hr_status_id == 1;
        $project = $request->filled('project') ? $request->project : ($employee->project ?? 'N/A');

        // Generate filename and path
        $fileName = "experience_letter_no_letterhead_" . ($isCurrentEmployee ? 'current' : 'previous') .
            "_{$employee->first_name}_{$employee->last_name}_" . time() . '.pdf';
        $downloadFileName = "experience_letter_no_letterhead_" . ($isCurrentEmployee ? 'current' : 'previous') .
            "_{$employee->first_name}_{$employee->last_name}.pdf";
        $employeeName = str_replace(' ', '_', strtolower($employee->full_name));
        $folderName = "hr/documentation/" . $employee->id . '-' . $employeeName . "/";
        $fullPath = $folderName . $fileName;

        $data = [
            'employee' => $employee,
            'designation' => $employee->designation,
            'date' => now()->format('F j, Y'),
            'signatory' => 'CH. ATIQ A. HUMAYUN',
            'signatory_position' => 'Deputy Manager (HR & Admin)',
            'is_current_employee' => $isCurrentEmployee,
            'project' => $project,
            'joining_date' => $employee->joining_date,
            'leaving_date' => !$isCurrentEmployee && $employee->last_working_date
                ? $employee->last_working_date
                : null,
            'path' => $fullPath,
            'show_letterhead' => false, // Flag to indicate no letterhead
            'show_signature' => false,  // Flag to indicate no signature
            'show_stamp' => false      // Flag to indicate no stamp
        ];

        $pdfContent = Pdf::loadView('hr.experience_letter.pdf', $data)
            ->setPaper('A4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('dpi', 300)
            ->setOption('defaultFont', 'DejaVu Sans')
            ->output();

        Storage::disk('public')->put($fullPath, $pdfContent);

        $documentation = HrDocumentation::create([
            'hr_employee_id' => $employee->id,
            'description' => 'Experience Letter (No Letterhead) (' . ($isCurrentEmployee ? 'current' : 'previous') .
                ') for ' . $employee->full_name . "-" . now(),
            'document_date' => now(),
            'file_name' => $fileName,
            'path' => $folderName,
            'size' => Storage::disk('public')->size($fullPath),
            'extension' => 'pdf',
            'content' => null
        ]);

        return response()->streamDownload(
            function () use ($pdfContent) {
                echo $pdfContent;
            },
            $downloadFileName,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $downloadFileName . '"',
            ]
        );
    }
}
