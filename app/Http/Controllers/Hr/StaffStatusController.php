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

        // SECTION 1: Employees in DB marked as active but not in Excel files
        $dbEmployeesNotInExcel = HrEmployee::whereNotIn('employee_no', $employeeNumbers)
            ->where('hr_status_id', 1) // Active in DB but not in Excel
            ->whereNotIn('id', $otherCompanyEmployees)
            ->with(['employeeCurrentDepartment' => function ($query) {
                $query->select('name');
            }])
            ->get(['id', 'employee_no', 'first_name', 'last_name']);

        // SECTION 2: Employees in Excel files but not in DB or marked inactive
        $excelEmployeesNotInDb = [];
        $dbEmployeeNumbers = HrEmployee::where('hr_status_id', 1) // Active employees
            ->whereNotIn('id', $otherCompanyEmployees)
            ->pluck('employee_no')
            ->toArray();

        // Find Excel employees not in DB or marked inactive
        foreach ($employeeNumbers as $empNo) {
            if (!in_array($empNo, $dbEmployeeNumbers)) {
                $excelEmployeesNotInDb[] = $empNo;
            }
        }

        // Generate text file content
        $content = "===== EMPLOYEES IN DATABASE BUT NOT IN EXCEL FILES (OR STATUS MISMATCH) =====\n";
        $content .= "Emp. No\tEmployee Name \t\tDepartment\n";
        foreach ($dbEmployeesNotInExcel as $employee) {
            $deptName = $employee->hrDepartment ? $employee->hrDepartment->name : 'N/A';
            $content .= "{$employee->employee_no}\t{$employee->first_name} {$employee->last_name}\t\t{$deptName}\n";
        }

        $content .= "\n\n===== EMPLOYEES IN EXCEL FILES BUT NOT IN DATABASE (OR INACTIVE) =====\n";
        $content .= "Employee Numbers\n";
        foreach ($excelEmployeesNotInDb as $empNo) {
            $content .= "{$empNo}\n";
        }

        // Get additional info for Excel employees not in DB
        if (!empty($excelEmployeesNotInDb)) {
            $content .= "\n\n===== DETAILS FOR EXCEL EMPLOYEES NOT IN DATABASE =====";
            $missingEmployees = HrEmployee::whereIn('employee_no', $excelEmployeesNotInDb)
                ->where('hr_status_id', '!=', 1) // Inactive in DB but in Excel
                ->with(['employeeCurrentDepartment' => function ($query) {
                    $query->select('name');
                }])
                ->get(['id', 'employee_no', 'first_name', 'last_name', 'hr_status_id']);

            if ($missingEmployees->isNotEmpty()) {
                $content .= "\n\nThese employees exist in database but are INACTIVE:\n";
                $content .= "Emp. No\tEmployee Name \t\tStatus\tDepartment\n";
                foreach ($missingEmployees as $employee) {
                    $deptName = $employee->hrDepartment ? $employee->hrDepartment->name : 'N/A';
                    $status = $employee->hr_status_id == 1 ? 'Active' : 'Inactive';
                    $content .= "{$employee->employee_no}\t{$employee->first_name} {$employee->last_name}\t\t{$status}\t{$deptName}\n";
                }
            } else {
                $content .= "\n\nThese employee numbers don't exist in database at all:\n";
                $content .= implode("\n", $excelEmployeesNotInDb);
            }
        }

        // Save to text file
        $filename = 'employee_status_comparison_' . now()->format('Ymd_His') . '.txt';
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
