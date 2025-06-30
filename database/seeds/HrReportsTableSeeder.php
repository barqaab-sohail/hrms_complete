<?php

namespace Database\Seeders;

use App\Models\Hr\HrReport;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class HrReportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reports = [
            [
                'name' => 'CNIC Expiry List',
                'route' => 'hrReports.cnicExpiryList',
                'description' => 'List of employees with expiring CNICs'
            ],
            [
                'name' => 'Mandatory Missing Document List',
                'route' => 'missingDocuments.list',
                'description' => 'List of employees with missing mandatory documents'
            ],
            [
                'name' => 'Complete Missing Document List',
                'route' => 'newmissingdocuments',
                'description' => 'Complete list of all missing documents'
            ],
            [
                'name' => 'Search Employee',
                'route' => 'hrReports.searchEmployee',
                'description' => 'Search employees by various criteria'
            ],
            [
                'name' => 'Report_1',
                'route' => 'hrReports.report_1',
                'description' => 'Basic employee information report'
            ],
            [
                'name' => 'Employee List (Full Details)',
                'route' => 'hr.reports.employee_list',
                'description' => 'Complete employee details report'
            ],
            [
                'name' => 'Employee Pictures',
                'route' => 'hrReports.pictureList',
                'description' => 'List of all employee pictures'
            ]
        ];

        foreach ($reports as $report) {
            HrReport::create($report);
        }
    }
}
