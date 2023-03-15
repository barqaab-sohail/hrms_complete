<?php

namespace App\Http\Controllers\MIS\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrDocumentation;

class EmployeeDocumentController extends Controller
{
    public function show($employeeId)
    {
        $employeeDocuments = HrDocumentation::where('hr_employee_id', $employeeId)->get();

        return response()->json($employeeDocuments);
    }
}
