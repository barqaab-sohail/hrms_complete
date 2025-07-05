<?php

namespace App\Http\Controllers\Hr;

use App\Models\Common\Bank;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Hr\HrDocumentation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use DataTables;
use Carbon\Carbon;

class BankLetterController extends Controller
{

    public function create()
    {
        $employees = HrEmployee::orderBy('first_name')->get();
        $banks = Bank::whereIn('name', [
            'Bank Alfalah Limited',
            'Faysal Bank Limited',
            'Standard Chartered Bank (Pakistan) Limited'
        ])
            ->orderBy('name')
            ->get();

        return view('hr.bank_letter.create', compact('employees', 'banks'));
    }

    public function preview(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:hr_employees,id',
            'bank_id' => 'required|exists:banks,id',
            'salary' => 'nullable|numeric'
        ]);

        $employee = HrEmployee::findOrFail($request->employee_id);
        $bank = Bank::findOrFail($request->bank_id);
        $salary =  $request->filled('salary') ? $request->salary : $employee->employeeCurrentSalary->total_salary ?? '';

        $data = [
            'employee' => $employee,
            'designation' => $employee->designation,
            'bank' => $bank,
            'salary' => $salary,
            'date' => now()->format('F j, Y'),
            'signatory' => 'CH. ATIQ A. HUMAYUN',
            'signatory_position' => 'Deputy Manager (HR & Admin)',
            'letterhead_url' => url('letterhead/barqaab_letterhead.jpg'),

        ];


        return view('hr.bank_letter.preview', $data);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:hr_employees,id',
            'bank_id' => 'required|exists:banks,id',
            'salary' => 'sometimes|numeric'
        ]);

        $employee = HrEmployee::findOrFail($request->employee_id);
        $bank = Bank::findOrFail($request->bank_id);
        $salary = $request->filled('salary') ? $request->salary : $employee->salary;

        // Generate filename and path
        $fileName = "barqaab_bank_letter_{$employee->first_name}_{$employee->last_name}_" . time() . '.pdf';
        $downloadFileName = "bank_letter_{$employee->first_name}_{$employee->last_name}.pdf";
        $employeeName = str_replace(' ', '_', strtolower($employee->full_name));
        $folderName = "hr/documentation/" . $employee->id . '-' . $employeeName . "/";
        $fullPath = $folderName . $fileName;

        $url = url('cardVerificationResult') . '/' . $employee->employee_no;
        $data = [
            'employee' => $employee,
            'bank' => $bank,
            'salary' => number_format($salary),
            'date' => now()->format('F j, Y'),
            'signatory' => 'CH. ATIQ A. HUMAYUN',
            'signatory_position' => 'Deputy Manager (HR & Admin)',
            'letterhead_url' => url('letterhead/barqaab_letterhead.jpg'),
            'stamp' => asset('letterhead/stamp.png'),
            'sign' => asset('letterhead/sign.png'),
            'path' => $fullPath
        ];

        // Generate the PDF content once
        $pdfContent = Pdf::loadView('hr.bank_letter.pdf', $data)
            ->setPaper('A4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('dpi', 300)
            ->setOption('defaultFont', 'DejaVu Sans')
            ->output();

        // Save the PDF to storage
        Storage::disk('public')->put($fullPath, $pdfContent);

        // Create HrDocumentation record
        $documentation = HrDocumentation::create([
            'hr_employee_id' => $employee->id,
            'description' => 'Bank Letter for ' . $employee->full_name . "-" . now(),
            'document_date' => now(),
            'file_name' => $fileName,
            'path' => $folderName,
            'size' => Storage::disk('public')->size($fullPath),
            'extension' => 'pdf',
            'content' => null
        ]);

        // Return the download response using the same content that was saved
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
            ->where('file_name', 'like', '%barqaab_bank_letter%')
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

    // public function generate(Request $request)
    // {
    //     $request->validate([
    //         'employee_id' => 'required|exists:hr_employees,id',
    //         'bank_id' => 'required|exists:banks,id',
    //         'salary' => 'sometimes|numeric'
    //     ]);

    //     $employee = HrEmployee::findOrFail($request->employee_id);
    //     $bank = Bank::findOrFail($request->bank_id);
    //     $salary = $request->filled('salary') ? $request->salary : $employee->salary;


    //     // Generate filename and path
    //     $fileName = $employee->id . '-' . "bank_letter_{$employee->first_name}_{$employee->last_name}_" . time() . '.' . 'pdf';
    //     $employeeName = str_replace(' ', '_', strtolower($employee->full_name));
    //     $folderName = "hr/documentation/" . $employee->id . '-' . $employeeName . "/";
    //     $fullPath =  $folderName . $fileName;


    //     $data = [
    //         'employee' => $employee,
    //         'bank' => $bank,
    //         'salary' => number_format($salary),
    //         'date' => now()->format('F j, Y'),
    //         'signatory' => 'CH. ATIQ A. HUMAYUN',
    //         'signatory_position' => 'Deputy Manager (HR & Admin)',
    //         'letterhead_url' => url('letterhead/barqaab_letterhead.jpg'),
    //         'stamp' => asset('letterhead/stamp.png'),
    //         'sign' => asset('letterhead/sign.png'),
    //         'path' => $fullPath,
    //     ];


    //     $pdf = Pdf::loadView('hr.bank_letter.pdf', $data);
    //     $pdf->setPaper('A4', 'portrait');
    //     $pdf->setOption('isHtml5ParserEnabled', true);
    //     $pdf->setOption('isRemoteEnabled', true);
    //     $pdf->setOption('dpi', 300);
    //     $pdf->setOption('defaultFont', 'DejaVu Sans');



    //     // Save the PDF to storage
    //     Storage::disk('public')->put($fullPath, $pdf->output());

    //     // Create HrDocumentation record
    //     $documentation = HrDocumentation::create([
    //         'hr_employee_id' => $employee->id,
    //         'description' => 'Bank Letter for ' . $employee->full_name . "-" . now(),
    //         'document_date' => now(),
    //         'file_name' => $fileName,
    //         'path' => $folderName,
    //         'size' => Storage::disk('public')->size($fullPath),
    //         'extension' => 'pdf',
    //         'content' => null // or you can store some content if needed
    //     ]);

    //     // Include the document path in the PDF if needed
    //     // You would need to modify your view to display this
    //     //  $data['document_path'] = $documentation->full_path;

    //     // Regenerate the PDF with the path if needed
    //     $pdf = Pdf::loadView('hr.bank_letter.pdf', $data);
    //     // Save again if you want the path in the PDF
    //     Storage::disk('public')->put($fullPath, $pdf->output());

    //     return $pdf->download($fileName);
    // }
}
