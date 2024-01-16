<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Models\Hr\HrEmployee;
use App\Models\Leave\LeAccumulative;

class LeAccumulativeImport implements ToModel, WithHeadingRow

{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $hrEmployee = HrEmployee::where('employee_no',$row['id'])->first();
        $row['hr_employee_id'] =  $hrEmployee->id;
        $row['date'] = \Carbon\Carbon::parse('2021-12-31')->format('Y-m-d');

        return new LeAccumulative([
            'hr_employee_id'  => $row['hr_employee_id'],
            'le_type_id'  => $row['leave_type'],
            'accumulative_total'  => $row['total'],
            'date'   => $row['date']
        ]);
    }
}
