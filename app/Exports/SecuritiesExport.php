<?php

namespace App\Exports;

use App\Models\Submission\Security;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SecuritiesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Security::with(['client', 'bank', 'submittedBy'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Type',
            'Bid Security Type',
            'Favor Of',
            'Date Issued',
            'Expiry Date',
            'Amount',
            'Project Name',
            'Reference Number',
            'Status',
            'Client',
            'Bank',
            'Submitted By',
            'Remarks'
        ];
    }

    public function map($security): array
    {
        return [
            $security->id,
            $security->type,
            $security->bid_security_type,
            $security->favor_of,
            $security->date_issued->format('Y-m-d'),
            $security->expiry_date ? $security->expiry_date->format('Y-m-d') : '',
            $security->amount,
            $security->project_name,
            $security->reference_number,
            $security->status,
            $security->client ? $security->client->name : '',
            $security->bank ? $security->bank->name : '',
            $security->submittedBy ? $security->submittedBy->name : '',
            $security->remarks
        ];
    }
}
