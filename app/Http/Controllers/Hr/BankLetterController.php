<?php

namespace App\Http\Controllers\Hr;

use DataTables;
use Carbon\Carbon;
use App\Models\Common\Bank;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Hr\HrDocumentation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BankLetterController extends Controller
{

    public function create()
    {
        $cacheTime = config('cache.employees_list_active', 1440); // 1440 minutes = 24 hours
        $employees = Cache::remember('employees_list_active', $cacheTime, function () {
            return HrEmployee::where('hr_status_id', 1)
                ->orderBy('first_name')
                ->with(['salayEffectiveDate', 'employeeCurrentSalary', 'employeeCurrentDesignation'])
                ->select('id', 'employee_no', 'first_name', 'last_name', 'hr_status_id')
                ->get();
        });

        $banks = Bank::orderBy('name')
            ->get();

        return view('hr.bank_letter.create', compact('employees', 'banks'));
    }

    public function preview(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:hr_employees,id',
            'bank_id' => 'required|exists:banks,id',
            'salary' => 'nullable|numeric',
            'letter_date' => 'nullable|date' // Add validation for date
        ]);

        $employee = HrEmployee::with('salayEffectiveDate', 'employeeCurrentSalary', 'employeeCurrentDesignation')->findOrFail($request->employee_id);
        $bank = Bank::findOrFail($request->bank_id);
        $salary =  $request->filled('salary') ? $request->salary : $employee->employeeCurrentSalary->total_salary ?? '';
        $isManualSalary = $request->filled('salary') ? true : false;
        $effectiveDate = \Carbon\Carbon::parse($employee->salayEffectiveDate?->effective_date)?->format('M d, Y');
        
        // Use custom date if provided, otherwise use current date
        $letterDate = $request->filled('letter_date') 
            ? \Carbon\Carbon::parse($request->letter_date)->format('F j, Y')
            : now()->format('F j, Y');

        $data = [
            'employee' => $employee,
            'effective_date' => $effectiveDate,
            'is_manual_salary' => $isManualSalary,
            'designation' => $employee->employeeCurrentDesignation->name,
            'bank' => $bank,
            'salary' => $salary,
            'date' => $letterDate, // Use the calculated date
            'letter_date_input' => $request->letter_date, // Pass the original input for form
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
            'salary' => 'sometimes|numeric',
            'letter_date' => 'nullable|date' // Add validation for date
        ]);

        $employee = HrEmployee::with('salayEffectiveDate', 'employeeCurrentSalary', 'employeeCurrentDesignation')->findOrFail($request->employee_id);
        $bank = Bank::findOrFail($request->bank_id);
        $salary = $request->filled('salary') ? $request->salary : $employee->salary;
        
        // Use custom date if provided, otherwise use current date
        $letterDate = $request->filled('letter_date') 
            ? \Carbon\Carbon::parse($request->letter_date)->format('F j, Y')
            : now()->format('F j, Y');

        // Generate filename and path
        $employeeName = str_replace(' ', '_', strtolower($employee->full_name));
        $fileName = "barqaab_bank_letter_{$employeeName}_" . time() . '.pdf';
        $downloadFileName = $fileName;
        $folderName = "hr/documentation/" . $employee->id . '-' . $employeeName . "/";
        $fullPath = $folderName . $fileName;

        $url = url('cardVerificationResult') . '/' . $employee->employee_no;
        $data = [
            'employee' => $employee,
            'bank' => $bank,
            'salary' => number_format($salary),
            'date' => $letterDate, // Use the calculated date
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
            'description' => 'Bank Letter for ' . $bank->name . "-" . $employee->full_name . "-" . now()->format('F d, Y, H:i:s'),
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
            ->editColumn('document_date', function ($row) {
                return $row->document_date ? \Carbon\Carbon::parse($row->document_date)->format('F d, Y') : '';
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