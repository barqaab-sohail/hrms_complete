<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Hr\HrEmployee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmployeeDocumentsExpiryReport;

class SendEmployeeDocumentsExpiryReport extends Command
{
    protected $signature = 'report:employee-documents-expiry';
    protected $description = 'Send Weekly consolidated employee documents expiry report every Monday';
    protected $recipients = [
        'hr@barqaab.com',
        'athar@barqaab.com',
        'muhammadrasheed2009@gmail.com',
        'sohail.afzal08@gmail.com',
        'sohail@barqaab.com'

    ];

    public function handle()
    {
        // Only run on Mondays
        // if (Carbon::now()->isMonday()) {
        $reportData = $this->gatherReportData();

        if (!empty($reportData)) {
            Mail::to($this->recipients)
                ->send(new EmployeeDocumentsExpiryReport($reportData));

            $this->info('Employee expiry report sent successfully.');
        } else {
            $this->info('No expiry data to report.');
        }
        // }
    }

    protected function gatherReportData()
    {
        return [
            'appointmentExpiry' => $this->appointmentExpiry(),
            'drivingLicenceExpiry' => $this->drivingLicenceExpiry(),
            'pecCardExpiry' => $this->pecCardExpiry(),
            'reportDate' => Carbon::now()->format('M d, Y')
        ];
    }

    // Your existing functions (modified to return data instead of JSON response)
    protected function appointmentExpiry()
    {
        $nextTenDays = Carbon::now()->addDays(10)->format('Y-m-d');
        $employees = HrEmployee::where('hr_status_id', 1)
            ->with('employeeAppointment', 'employeeOffice', 'employeeProject')
            ->get();

        $data = [];

        foreach ($employees as $employee) {
            if ($employee->employeeAppointment->expiry_date ?? '' != '') {
                if ($employee->employeeAppointment->expiry_date < $nextTenDays) {
                    $data[] = [
                        "employee_name" => employeeFullName($employee->id),
                        "employee_project" => $employee->employeeProject->last()->name ?? '',
                        "employee_office" => $employee->employeeOffice->last()->name ?? '',
                        "expiry_date" => Carbon::parse($employee->employeeAppointment->expiry_date)->format('M d, Y'),
                        "mobile" => $employee->hrContactMobile->mobile ?? '',
                        "type" => "Appointment Letter"
                    ];
                }
            }
        }

        if (!empty($data)) {
            usort($data, function ($a, $b) {
                return strtotime($a['expiry_date']) - strtotime($b['expiry_date']);
            });
        }

        return $data;
    }

    protected function drivingLicenceExpiry()
    {
        $nextTenDays = Carbon::now()->addDays(10)->format('Y-m-d');
        $employees = HrEmployee::where('hr_status_id', 1)
            ->with('employeeDesignation', 'employeeProject', 'employeeOffice', 'hrDriving')
            ->get();

        $data = [];

        foreach ($employees as $employee) {
            if (($employee->employeeDesignation->last()->name ?? '') == 'Driver') {
                if (!empty($employee->hrDriving->licence_expiry)) {
                    if ($employee->hrDriving->licence_expiry < $nextTenDays) {
                        $data[] = [
                            "employee_name" => employeeFullName($employee->id),
                            "employee_project" => $employee->employeeProject->last()->name ?? '',
                            "employee_office" => $employee->employeeOffice->last()->name ?? '',
                            "expiry_date" => Carbon::parse($employee->hrDriving->licence_expiry)->format('M d, Y'),
                            "mobile" => $employee->hrContactMobile->mobile ?? '',
                            "type" => "Driving Licence"
                        ];
                    }
                }
            }
        }

        if (!empty($data)) {
            usort($data, function ($a, $b) {
                return strtotime($a['expiry_date']) - strtotime($b['expiry_date']);
            });
        }

        return $data;
    }

    protected function pecCardExpiry()
    {
        $nextTenDays = Carbon::now()->addDays(10)->format('Y-m-d');
        $employees = HrEmployee::where('hr_status_id', 1)
            ->with('hrMembership', 'employeeOffice', 'employeeProject')
            ->get();

        $data = [];

        foreach ($employees as $employee) {
            if ($employee->hrMembership->expiry ?? '' != '') {
                if ($employee->hrMembership->expiry < $nextTenDays) {
                    $data[] = [
                        "employee_name" => employeeFullName($employee->id),
                        "employee_project" => $employee->employeeProject->last()->name ?? '',
                        "employee_office" => $employee->employeeOffice->last()->name ?? '',
                        "expiry_date" => Carbon::parse($employee->hrMembership->expiry)->format('M d, Y'),
                        "mobile" => $employee->hrContactMobile->mobile ?? '',
                        "pec" => $employee->hrMembership->membership_no ?? '',
                        "type" => "PEC Card"
                    ];
                }
            }
        }

        if (!empty($data)) {
            usort($data, function ($a, $b) {
                return strtotime($a['expiry_date']) - strtotime($b['expiry_date']);
            });
        }

        return $data;
    }

    protected function leaveStaffActiveStatus()
    {
        $leaves = statusLeaveEmployee();
        $data = [];

        foreach ($leaves as $leave) {
            $employee = HrEmployee::with('employeeCurrentProject', 'employeeCurrentOffice', 'hrContactMobile')
                ->find($leave->hr_employee_id);

            $data[] = [
                "employee_name" => employeeFullName($employee->id),
                "employee_project" => $employee->employeeCurrentProject->name ?? '',
                "employee_office" => $employee->employeeCurrentOffice->name ?? '',
                "leave_from" => Carbon::parse($leave->from)->format('M d, Y'),
                "leave_to" => Carbon::parse($leave->to)->format('M d, Y'),
                "mobile" => $employee->hrContactMobile->mobile ?? '',
                "type" => "On Leave"
            ];
        }

        return $data;
    }
}
