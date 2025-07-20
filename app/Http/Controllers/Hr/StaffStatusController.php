<?php

namespace App\Http\Controllers\Hr;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use App\Http\Controllers\Controller;
use App\Models\Hr\HrEmployeeCompany;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Exports\JoiningDateMismatchExport;

class StaffStatusController extends Controller
{
    public function showUploadForm()
    {
        return view('hr.checking.create');
    }

    public function processFiles(Request $request)
    {

        $request->validate([
            'excel_files.*' => 'required|mimes:xls,xlsx|max:2048'
        ]);


        $employeeNumbers = [];

        // Process each uploaded file
        foreach ($request->file('excel_files') as $file) {
            $employeeNumbers = array_merge(
                $employeeNumbers,
                $this->extractEmployeeNumbers($file)
            );
        }

        // Remove duplicates and Final List of Employee Numbers of HR Excel Files
        $employeeNumbers = array_unique($employeeNumbers);

        // HRMS Employee Numbers who status is Not Active
        $otherCompanyEmployees = HrEmployeeCompany::where('partner_id', '!=', 1)
            ->pluck('hr_employee_id');

        // Get employees with incorrect status
        $employees = HrEmployee::whereNotIn('employee_no', $employeeNumbers)
            ->where('hr_status_id', 1) // Not Active
            ->whereNotIn('id', $otherCompanyEmployees)
            ->with(['employeeCurrentDepartment' => function ($query) {
                $query->select('name',);
            }])
            ->get(['id', 'employee_no', 'first_name', 'last_name']);

        // Generate text file content
        $content = "Emp. No\tEmployee Name \t\tDepartment\n";
        foreach ($employees as $employee) {

            if ($employee->hrDepartment) {
                $content .= "{$employee->employee_no}\t{$employee->first_name} {$employee->last_name}\t\t{$employee->hrDepartment->name}\n";
            } else {
                $content .= "{$employee->employee_no}\t{$employee->first_name} {$employee->last_name}\t\tN/A\n";
            }

            // $content .= "{$employee->employee_no}\t{$employee->first_name} {$employee->last_name}\t{$employee->employee_no}\n";
        }

        // Save to text file
        $filename = 'status_discrepancies_' . now()->format('Ymd_His') . '.txt';
        Storage::put($filename, $content);

        // Download the file
        return Storage::download($filename);
    }

    private function extractEmployeeNumbers($filePath)
    {
        $employeeData = [];

        try {
            // Load the Excel file
            $spreadsheet = IOFactory::load($filePath);

            // Get all sheet names
            $sheetNames = $spreadsheet->getSheetNames();

            foreach ($sheetNames as $sheetName) {
                $worksheet = $spreadsheet->getSheetByName($sheetName);

                // Get the highest row and column
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                // Iterate through each row
                for ($row = 1; $row <= $highestRow; $row++) {
                    // Get cell value from Column B
                    $employeeNo = $worksheet->getCellByColumnAndRow(2, $row)->getValue();

                    // Skip if empty
                    if (empty($employeeNo)) {
                        continue;
                    }

                    // Convert to string and trim
                    $employeeNo = trim((string)$employeeNo);

                    // Check if the value is numeric (allows integers and numeric strings)
                    if (is_numeric($employeeNo)) {
                        // If you want only integers, you can add this additional check:
                        // if (ctype_digit($employeeNo)) {
                        $employeeData[] = $employeeNo;
                    }
                }
            }

            // Remove duplicates
            $employeeData = array_unique($employeeData);

            // Reset array keys and maintain original order
            $employeeData = array_values($employeeData);
        } catch (\Exception $e) {
            // Handle any exceptions
            return [
                'error' => 'Error processing file: ' . $e->getMessage()
            ];
        }

        return $employeeData;
    }

    public function joiningDateMisMatched()
    {
        $misMatchDate = [];
        $hrEmployees = HrEmployee::where('hr_status_id', 1)->get();

        foreach ($hrEmployees as $hrEmployee) {
            $joiningReportDate = $hrEmployee->joiningReportDocument->document_date ?? 'Joining Report not found';

            $joiningDate = '';

            if (!empty($hrEmployee->joining_date) && $hrEmployee->joining_date !== 'N/A') {
                try {
                    $joiningDate = \Carbon\Carbon::parse($hrEmployee->joining_date)->format('Y-m-d');
                } catch (\Exception $e) {
                    $joiningDate = 'Invalid date format';
                }
            } else {
                $joiningDate = 'Joining Date not found';
            }

            if ($joiningReportDate != $joiningDate) {
                $misMatchDate[] = [
                    'employee_no' => $hrEmployee->employee_no,
                    'employee_name' => $hrEmployee->first_name . ' ' . $hrEmployee->last_name,
                    'joining_date' => $joiningDate,
                    'joining_report_date' => $joiningReportDate
                ];
            }
        }

        return Excel::download(new JoiningDateMismatchExport($misMatchDate), 'joining_date_mismatches.xlsx');
    }
}
