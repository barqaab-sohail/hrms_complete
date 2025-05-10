<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Hr\HrEmployee;
use Illuminate\Console\Command;
use App\Models\Hr\HrDocumentName;
use App\Mail\MissingDocumentsReport;
use App\Models\Hr\HrEmployeeCompany;
use Illuminate\Support\Facades\Mail;
use DB;

class SendMissingDocumentsReport extends Command
{
    protected $signature = 'report:missing-documents';
    protected $description = 'Send consolidated missing documents report every Monday';

    // List of recipients
    protected $recipients = [
        // 'hr@barqaab.com',
        // 'athar@barqaab.com',
        // 'muhammadrasheed2009@gmail.com',
        'sohail.afzal08@gmail.com',
        'sohail@barqaab.com'
    ];

    public function handle()
    {
        // Only run on Mondays
        // if (Carbon::now()->isMonday()) {
        $missingDocuments = $this->getEmployeesWithMissingDocuments();

        if (!empty($missingDocuments)) {
            Mail::to($this->recipients)
                ->send(new MissingDocumentsReport($missingDocuments));

            $this->info('Missing documents report sent successfully.');
        } else {
            $this->info('No missing documents to report.');
        }
        //  }
    }

    protected function getEmployeesWithMissingDocuments()
    {
        $requiredDocumentTitles = [
            "CNIC Front",
            "Picture",
            "Signed Appointment Letter",
            "HR Form",
            "Joining Report",
            "Educational Documents",
        ];

        $pecSpecificDocumentTitles = [
            "PEC Front",
            "Engineering Degree BSc",
        ];

        $excludedDesignations = [
            "Kitchen Helper",
            "Security Guard",
            "Office Helper",
            "Utility Person",
            "Record Keeper",
            "Driver",
            "Electrician",
            "Sanitary Worker Part Time",
            "Cook",
            "Sanitary Worker",
            "Naib Qasid",
            "ChowkidarWatchman",
            "Line Foreman",
            "Patwari",
            "Khalasi",
            "Sweeper Sanitary Worker",
            "Driver Cum Utility Person Part Time",
            "Part Time Gardner",
            "Sweeper",
            "Office Boy Cum Mali",
            "Recovery Officer",
            "Utility Person Part Time",
            "Field Helper",
            "Sweeper (Part Time)",
            "Chakbandi Coordinator",
            "Hastel Attended",
            "Office Helper",
            "Sanitary Worker Part Time",
            "Utility Person Cook"
        ];

        // Fetch required + PEC document IDs
        $allRequiredDocumentTitles = array_merge($requiredDocumentTitles, $pecSpecificDocumentTitles);

        $allRequiredDocuments = HrDocumentName::whereIn('name', $allRequiredDocumentTitles)
            ->get()
            ->keyBy('name');

        $missingData = [];

        $otherCompanyEmployeeIds = HrEmployeeCompany::where('partner_id', '!=', 1)->pluck('hr_employee_id')->toArray();

        $employees = HrEmployee::with(['employeeCurrentDesignation', 'membership', 'hrContactMobile', 'employeeCurrentDepartment', 'employeeCurrentProject'])
            ->whereNotIn('id', $otherCompanyEmployeeIds)
            ->where('hr_status_id', 1)
            ->get();

        foreach ($employees as $employee) {
            $submittedDocumentIds = DB::table('hr_document_name_hr_documentation')
                ->where('hr_employee_id', $employee->id)
                ->pluck('hr_document_name_id')
                ->toArray();

            $missingDocs = [];

            // Check default required documents
            foreach ($requiredDocumentTitles as $docTitle) {
                if (
                    $docTitle === "Educational Documents" &&
                    in_array($employee->employeeCurrentDesignation->name ?? '', $excludedDesignations)
                ) {
                    continue;
                }

                $docId = $allRequiredDocuments[$docTitle]->id ?? null;

                if ($docId && !in_array($docId, $submittedDocumentIds)) {
                    $missingDocs[] = $docTitle;
                }
            }

            // Check PEC specific documents if applicable
            if ($employee->membership?->name === "Pakistan Engineering Council") {
                foreach ($pecSpecificDocumentTitles as $docTitle) {
                    $docId = $allRequiredDocuments[$docTitle]->id ?? null;

                    if ($docId && !in_array($docId, $submittedDocumentIds)) {
                        $missingDocs[] = $docTitle;
                    }
                }
            }

            if (!empty($missingDocs)) {
                $project = '';
                if ($employee->employeeCurrentProject?->name == 'overhead') {
                    $project = $employee->employeeCurrentOffice?->name;
                } else {
                    $project = $employee->employeeCurrentProject?->name ?? 'N/A';
                }

                $missingData[] = [
                    'employee_no' => $employee->employee_no,
                    'employee_name' => $employee->first_name . ' ' . $employee->last_name ?? 'N/A',
                    'designation' => $employee->employeeCurrentDesignation->name ?? 'N/A',
                    'joining_date' => $employee->employeeAppointment?->joining_date
                        ? \Carbon\Carbon::parse($employee->employeeAppointment?->joining_date)->format('F d, Y')
                        : 'N/A',
                    'contact_number' => $employee->hrContactMobile->mobile ?? 'N/A',
                    'division' => $employee->employeeCurrentDepartment->name ?? 'N/A',
                    'project' => $project,
                    'missing_documents' => $missingDocs,
                    'is_pec' => $employee->membership?->name === "Pakistan Engineering Council",
                ];
            }
        }

        // Sort by employee name
        usort($missingData, function ($a, $b) {
            return strcmp($a['employee_name'], $b['employee_name']);
        });

        return $missingData;
    }
}
