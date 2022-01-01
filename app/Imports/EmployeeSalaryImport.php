<?php

namespace App\Imports;

use App\Models\Hr\EmployeeSalary;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Models\Hr\HrSalary;
use App\Models\Hr\HrEmployee;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmployeeSalaryImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $hrEmployee = HrEmployee::where('employee_no',$row['no'])->first();
        
        $row['hr_employee_id'] =  $hrEmployee->id;

        //remove comma from current salary column.
        $currentSalary = intval(str_replace( ',', '', $row['currentsalary']));

        $hrSalary = HrSalary::where('total_salary',$currentSalary)->first();
            if(!$hrSalary){
                $hrSalary = HrSalary::create(['total_salary'=>$currentSalary]);
            }
        $row['hr_salary_id'] = $hrSalary->id;
       
        $row['date'] = \Carbon\Carbon::parse('2021-12-31')->format('Y-m-d');

        return new EmployeeSalary([
            'hr_salary_id'  => $row['hr_salary_id'],
            'hr_employee_id'  => $row['hr_employee_id'],
            'effective_date'   => $row['date']
        ]);
    }


    // public function rules(): array
    // {
    //     return [
    //         'hr_salary_id'=>'required|numeric',
    //         'hr_employee_id'=>'required|numeric',
    //         'effective_date'=>'required|date'
    //     ];
    // }
}
