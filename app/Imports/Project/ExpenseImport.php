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
        // dd($collection[1][0]);
        $isValue2Null = true;
        $reimbursementExpensesKey = '';
        $nonReimbursementExpensesKey = '';
        $reimbursementSalaryKey = '';
        $nonReimbursementSalaryKey = '';
        foreach ($collection as $key => $value) {

            if ($value->contains("REIMBURSEABLE EXPENS")) {
                $reimbursementExpensesKey = $key;
            }
            if ($value->contains("NON REMIBURSABLE EXP")) {
                $nonReimbursementExpensesKey = $key;
            }
            if ($value->contains("REIMBURSEABLE SALARIE")) {
                $reimbursementSalaryKey = $key;
            }
            if ($value->contains("NON REIMBURSEABLESAL")) {
                $nonReimbursementSalaryKey = $key;
            }
        }

        $months = [];
        $expenses = [];
        $projectNo = '';
        $year = '';
        $nextYear = '';
        $reportName = '';
        $reimbursementExpenses = [];
        $nonReimbursementExpenses = [];
        $reimbursementSalary = [];
        $nonReimbursementSalary = [];
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
            if ($key == $reimbursementSalaryKey) {
                if ($value[2] != null) {
                    $isValue2Null = false;
                }
                array_push($reimbursementSalary, $value[1], $value[3], $value[4], $value[5], $value[6], $value[7], $value[8], $value[9], $value[10], $value[11], $value[12], $value[13]);
            }
            if ($key == $reimbursementExpensesKey) {
                if ($value[2] != null) {
                    $isValue2Null = false;
                }
                array_push($reimbursementExpenses, $value[1], $value[3], $value[4], $value[5], $value[6], $value[7], $value[8], $value[9], $value[10], $value[11], $value[12], $value[13]);
            }
            if ($key == $nonReimbursementExpensesKey) {
                if ($value[2] != null) {
                    $isValue2Null = false;
                }
                array_push($nonReimbursementExpenses, $value[1], $value[3], $value[4], $value[5], $value[6], $value[7], $value[8], $value[9], $value[10], $value[11], $value[12], $value[13]);
            }
            if ($key == $nonReimbursementSalaryKey) {
                if ($value[2] != null) {
                    $isValue2Null = false;
                }
                array_push($nonReimbursementSalary, $value[1], $value[3], $value[4], $value[5], $value[6], $value[7], $value[8], $value[9], $value[10], $value[11], $value[12], $value[13]);
            }
            if ($key == 17) {
                array_push($expenses, $value[1], $value[3], $value[4], $value[5], $value[6], $value[7], $value[8], $value[9], $value[10], $value[11], $value[12], $value[13]);
            }
        }
        $directCost = array_map(function () {
            return array_sum(func_get_args());
        }, $reimbursementExpenses, $nonReimbursementExpenses);
        $salaryCost = array_map(function () {
            return array_sum(func_get_args());
        }, $reimbursementSalary, $nonReimbursementSalary);

        $this->data = ['isColumnTwoEmpty' => $isValue2Null, 'projectNo' => $projectNo, 'reportName' => $reportName, 'months' => $months, 'salary' => $salaryCost, 'directCost' => $directCost];
    }
}
