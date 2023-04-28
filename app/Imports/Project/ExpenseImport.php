<?php

namespace App\Imports\Project;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;

class ExpenseImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public $data;

    public function collection(Collection $collection)
    {

        $reimbursementExpensesKey = '';
        $nonReimbursementExpensesKey = '';
        $reimbursementSalaryKey = '';
        $nonReimbursementSalaryKey = '';

        foreach ($collection as $key => $value) {
            if ($value[$key] == "REIMBURSEABLE EXPENS") {
                $reimbursementExpensesKey = $key;
            } else if ($value[$key] == "REIMBURSEABLE SALARIE") {
                $reimbursementSalaryKey = $key;
            } else if ($value[$key] == "NON REMIBURSABLE EXP") {
                $nonReimbursementExpensesKey = $key;
            } else if ($value[$key] == "NON REIMBURSEABLESAL") {
                $nonReimbursementSalaryKey = $key;
            }
        }
        dd([$reimbursementSalaryKey, $reimbursementExpensesKey,  $nonReimbursementSalaryKey,  $nonReimbursementExpensesKey]);

        $months = [];
        $expenses = [];
        $projectNo = '';
        $year = '';
        $nextYear = '';
        $reportName = '';
        $reimbursementExpenses = [];
        $nonReimbursementExpenses = [];
        $reimbursementSalary = [];
        $directCost = [];
        foreach ($collection as $key => $value) {
            if ($key == 1) {
                $reportName = $value[0];
            }
            if ($key == 3) {
                $projectNo = substr($value[0], -4);
            }
            if ($key == 6) {
                $year = '20' . $value[0];
                $nextYear = '20' . $value[0] + 1;
                array_push($months, 'Jul' . '-' . $year, 'Aug' . '-' . $year, 'Sep' . '-' . $year, 'Oct' . '-' . $year, 'Nov' . '-' . $year, 'Dec' . '-' . $year, 'Jan' . '-' . $nextYear, 'Feb' . '-' . $nextYear, 'Mar' . '-' . $nextYear, 'Apr' . '-' . $nextYear, 'May' . '-' . $nextYear, 'Jun' . '-' . $nextYear);
            }
            if ($key == 14) {
                array_push($reimbursementSalary, $value[1], $value[3], $value[4], $value[5], $value[6], $value[7], $value[8], $value[9], $value[10], $value[11], $value[12], $value[13]);
            }
            if ($key == 15) {
                array_push($reimbursementExpenses, $value[1], $value[3], $value[4], $value[5], $value[6], $value[7], $value[8], $value[9], $value[10], $value[11], $value[12], $value[13]);
            }
            if ($key == 16) {
                array_push($nonReimbursementExpenses, $value[1], $value[3], $value[4], $value[5], $value[6], $value[7], $value[8], $value[9], $value[10], $value[11], $value[12], $value[13]);
            }
            if ($key == 17) {
                array_push($expenses, $value[1], $value[3], $value[4], $value[5], $value[6], $value[7], $value[8], $value[9], $value[10], $value[11], $value[12], $value[13]);
            }
        }
        $directCost = array_map(function () {
            return array_sum(func_get_args());
        }, $reimbursementExpenses, $nonReimbursementExpenses);


        $this->data = ['projectNo' => $projectNo, 'reportName' => $reportName, 'months' => $months, 'salary' => $reimbursementSalary, 'directCost' => $directCost];
    }
}
