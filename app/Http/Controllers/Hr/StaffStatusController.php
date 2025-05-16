<?php

namespace App\Http\Controllers\Hr;

use Illuminate\Http\Request;
use App\Models\Hr\HrEmployee;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

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

        // Remove duplicates
        $employeeNumbers = array_unique($employeeNumbers);

        // Get employees with incorrect status
        $employees = HrEmployee::whereNotIn('employee_no', $employeeNumbers)
            ->where('hr_status_id', 1) // Not Active
            ->get(['employee_no', 'first_name', 'last_name']);

        // Generate text file content
        $content = "Employee No\tEmployee Name\n";
        foreach ($employees as $employee) {
            $content .= "{$employee->employee_no}\t{$employee->first_name} {$employee->last_name}\n";
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
}
