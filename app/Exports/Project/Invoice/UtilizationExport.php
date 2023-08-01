<?php

namespace App\Exports\Project\Invoice;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use App\Models\Project\Invoice\PrMmUtilization;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use DB;

class UtilizationExport implements FromQuery, WithHeadings, WithMapping
{
    public $prDetailId;

    public function __construct(int $prDetailId)
    {
        $this->prDetailId = $prDetailId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */

    public function headings(): array
    {

        // according to users table

        return [
            "Position",
            "Employee Name",
            "Invoice No",
            "Month",
            "Man Month",
            "Billing Rate",
            "Total Amount",
        ];
    }

    public function map($prMmUtilization): array
    {

        return [
            $prMmUtilization->hrDesignation->name,
            $prMmUtilization->hrEmployee->full_name,
            $prMmUtilization->invoice_id,
            $prMmUtilization->month_year,
            $prMmUtilization->man_month,
            number_format($prMmUtilization->billing_rate, 0),
            number_format(round($prMmUtilization->man_month *  $prMmUtilization->billing_rate, 0), 0),
        ];
    }

    public function query()
    {
        $prMmUtilization =  PrMmUtilization::query()->select('pr_position_id', 'hr_employee_id', 'invoice_id', 'month_year', 'man_month', 'billing_rate')->where('pr_detail_id', $this->prDetailId);

        return $prMmUtilization;
    }
}
