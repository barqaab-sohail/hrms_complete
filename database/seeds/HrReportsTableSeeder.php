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
                'order' => 1,
                'description' => 'List of CNIC Expiry Employees'
            ],
            [
                'name' => 'Mandatory Missing Document List',
                'route' => 'missingDocuments.list',
                'order' => 2,
                'description' => 'List of Mandatory Documents Missing from Employees'
            ],
            [
                'name' => 'Complete Missing Document List',
                'route' => 'newmissingdocuments',
                'order' => 3,
                'description' => 'Complete list of all missing documents from employees'
            ],
            [
                'name' => 'Search Employee',
                'route' => 'hrReports.searchEmployee',
                'order' => 4,
                'description' => 'Search employees by various criteria'
            ],
            [
                'name' => 'Report_1',
                'route' => 'hrReports.report_1',
                'order' => 5,
                'description' => 'Employee Maximum Information Report'
            ],
            [
                'name' => 'Employee List (Full Details)',
                'route' => 'hr.reports.employee_list',
                'order' => 6,
                'description' => 'Employee Maximum Information Report with Search Options'
            ],
            [
                'name' => 'Employee Pictures',
                'route' => 'hrReports.pictureList',
                'order' => 7,
                'description' => 'List of all employee pictures'
            ]
        ];

        foreach ($reports as $report) {
            HrReport::create($report);
        }
    }
}
