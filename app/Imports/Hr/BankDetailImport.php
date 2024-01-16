<?php

namespace App\Imports\Hr;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Models\Hr\EmployeeBank;
use App\Models\Hr\HrEmployee;
use App\Models\Common\Bank;

class BankDetailImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param Collection $collection
     */
    public function model(array $row)
    {
        $cnic = $row['cnic'];
        $cnic = substr($cnic, 0, 5) . "-" . substr($cnic, 5);
        $cnic = substr($cnic, 0, 13) . "-" . substr($cnic, 13);

        $employee = HrEmployee::where('cnic', $cnic)->first();

        if ($employee) {
            return new EmployeeBank([
                'hr_employee_id'   => $employee->id,
                'bank_id'  => $row['bank_id'],
                'account_no'  => $row['account'],


            ]);
        }
    }

    public function rules(): array
    {
        return [
            'bank_id' => 'required|numeric',
            'cnic' => 'required',
            'account' => 'required|numeric',
        ];
    }
}
