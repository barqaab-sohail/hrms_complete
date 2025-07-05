<?php

namespace App\Http\Controllers\Hr;

use DataTables;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Hr\HrDocumentation;
use App\Http\Controllers\Controller;
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
            'project' => 'nullable|string|max:555'
        ]);

        $employee = HrEmployee::findOrFail($request->employee_id);

        // Determine letter type based on hr_status_id
        $isCurrentEmployee = $employee->hr_status_id == "Active";

        // Use manual values if provided, otherwise get from employee
        $project = $request->filled('project') ? $request->project : ($employee->project ?? 'N/A');
        $joiningDate = $request->filled('joining_date') ? $request->joining_date : ($employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('F d, Y') : '');
        $leavingDate = !$isCurrentEmployee
            ? ($request->filled('leaving_date') ? $request->leaving_date : ($employee->last_working_date ? \Carbon\Carbon::parse($employee->last_working_date)->format('F d, Y') : ''))
            : null;
        $letterDate = $request->filled('letter_date') ? $request->letter_date : \Carbon\Carbon::now()->format('F d, Y');

        $data = [
            'employee' => $employee,
            'designation' => $employee->designation,
            'date' => $letterDate,
            'signatory' => 'CH. ATIQ A. HUMAYUN',
            'signatory_position' => 'Deputy Manager (HR & Admin)',
            'letterhead_url' => url('letterhead/barqaab_letterhead.jpg'),
            'is_current_employee' => $isCurrentEmployee,
            'project' => $project,
            'joining_date' => $joiningDate,
            'leaving_date' => $leavingDate
        ];

        return view('hr.experience_letter.preview', $data);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:hr_employees,id',
            'project' => 'nullable|string|max:555'
        ]);

        $employee = HrEmployee::findOrFail($request->employee_id);
        $isCurrentEmployee = $employee->hr_status_id == "Active";
        // Use manual values if provided, otherwise get from employee
        $project = $request->filled('project') ? $request->project : ($employee->project ?? 'N/A');
        $joiningDate = $request->filled('joining_date') ? $request->joining_date : ($employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('F d, Y') : '');
        $leavingDate = !$isCurrentEmployee
            ? ($request->filled('leaving_date') ? $request->leaving_date : ($employee->last_working_date ? \Carbon\Carbon::parse($employee->last_working_date)->format('F d, Y') : ''))
            : null;
        $letterDate = $request->filled('date') ? $request->date : \Carbon\Carbon::now()->format('F d, Y');



        // Generate filename and path
        $fileName = "barqaab_experience_letter_" . ($isCurrentEmployee ? 'current' : 'previous') .
            "_{$employee->first_name}_{$employee->last_name}_" . time() . '.pdf';
        $downloadFileName = "experience_letter_" . ($isCurrentEmployee ? 'current' : 'previous') .
            "_{$employee->first_name}_{$employee->last_name}.pdf";
        $employeeName = str_replace(' ', '_', strtolower($employee->full_name));
        $folderName = "hr/documentation/" . $employee->id . '-' . $employeeName . "/";
        $fullPath = $folderName . $fileName;



        $data = [
            'employee' => $employee,
            'designation' => $employee->designation,
            'date' => $letterDate,
            'signatory' => 'CH. ATIQ A. HUMAYUN',
            'signatory_position' => 'Deputy Manager (HR & Admin)',
            'letterhead_url' => url('letterhead/barqaab_letterhead.jpg'),
            'stamp' => asset('letterhead/stamp.png'),
            'sign' => asset('letterhead/sign.png'),
            'is_current_employee' => $isCurrentEmployee,
            'project' => $project,
            'joining_date' => $joiningDate,
            'leaving_date' => $leavingDate,
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
            'description' => 'BARQAAB Experience Letter (' . ($isCurrentEmployee ? 'current' : 'previous') .
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
            'project' => 'nullable|string|max:555'
        ]);

        $employee = HrEmployee::findOrFail($request->employee_id);
        $isCurrentEmployee = $employee->hr_status_id == 1;
        $project = $request->filled('project') ? $request->project : ($employee->project ?? 'N/A');
        $joiningDate = $request->filled('joining_date') ? $request->joining_date : ($employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('F d, Y') : '');
        $leavingDate = !$isCurrentEmployee
            ? ($request->filled('leaving_date') ? $request->leaving_date : ($employee->last_working_date ? \Carbon\Carbon::parse($employee->last_working_date)->format('F d, Y') : ''))
            : null;
        $letterDate = $request->filled('date') ? $request->date : \Carbon\Carbon::now()->format('F d, Y');

        // Generate filename and path
        $fileName = "barqaab_experience_letter_no_letterhead_" . ($isCurrentEmployee ? 'current' : 'previous') .
            "_{$employee->first_name}_{$employee->last_name}_" . time() . '.pdf';
        $downloadFileName = "experience_letter_no_letterhead_" . ($isCurrentEmployee ? 'current' : 'previous') .
            "_{$employee->first_name}_{$employee->last_name}.pdf";
        $employeeName = str_replace(' ', '_', strtolower($employee->full_name));
        $folderName = "hr/documentation/" . $employee->id . '-' . $employeeName . "/";
        $fullPath = $folderName . $fileName;

        $data = [
            'employee' => $employee,
            'designation' => $employee->designation,
            'date' => $letterDate,
            'signatory' => 'CH. ATIQ A. HUMAYUN',
            'signatory_position' => 'Deputy Manager (HR & Admin)',
            'is_current_employee' => $isCurrentEmployee,
            'project' => $project,
            'joining_date' => $joiningDate,
            'leaving_date' => $leavingDate,
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
            'description' => 'BARQAAB Experience Letter (No Letterhead) (' . ($isCurrentEmployee ? 'current' : 'previous') .
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


    public function list(Request $request)
    {
        $documentation = HrDocumentation::where('hr_employee_id', $request->employee_id)
            ->where('file_name', 'like', '%barqaab_experience_letter%')
            ->orderBy('document_date', 'desc')
            ->get();

        return DataTables::of($documentation)
            ->addIndexColumn()
            ->addColumn('document', function ($row) {
                if ($row->extension != 'pdf') {
                    return '<img id="ViewIMG" src="' . $row->full_path . '" href="' . $row->full_path . '" width="30/" style="cursor: pointer;">';
                } else {
                    return '<img id="ViewPDF" src="https://hrms.barqaab.pk/Massets/images/document.png" href="' . $row->full_path . '" width="30/" style="cursor: pointer;">';
                }
            })
            ->addColumn('copy_link', function ($row) {
                return '<a class="copyLink" link="' . $row->tiny_url . '" style="cursor: auto;" title="Click for Copy Link"><img src="https://hrms.barqaab.pk/Massets/images/copyLink.png" width="30"></a>';
            })
            ->addColumn('Delete', function ($row) {
                // Calculate if the document was created more than 1 hour ago
                $createdAt = Carbon::parse($row->created_at);
                $oneHourAgo = Carbon::now()->subHour();

                if ($createdAt->lt($oneHourAgo)) {
                    // More than 1 hour old - disable the button
                    $btn = '<button class="btn btn-danger btn-sm" disabled>Delete</button>';
                } else {
                    // Within 1 hour - enable the button
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteDocument">Delete</a>';
                }

                return $btn;
            })
            ->rawColumns(['document', 'copy_link', 'Edit', 'Delete'])
            ->make(true);
    }
}
