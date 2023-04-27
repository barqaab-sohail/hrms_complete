<?php

namespace App\Imports\Project;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ExpenseImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $months = [];
        $expenses = [];
        $projectCode = '';
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
                $projectCode = substr($value[0], -4);
            }
            if ($key == 6) {
                $year = '20' . $value[0];
                $nextYear = '20' . $value[0] + 1;
                array_push($months, 'Jul' . '-' . $year, 'Aug' . '-' . $year, 'Sep' . '-' . $year, 'Oct' . '-' . $year, 'Nov' . '-' . $year, 'Dec' . '-' . $year, 'Jan' . '-' . $nextYear, 'Feb' . '-' . $nextYear, 'Mar' . '-' . $nextYear, 'Apr' . '-' . $nextYear, 'May' . '-' . $nextYear, 'Jun' . '-' . $nextYear);
            }
            // if ($key == 7) {


            //     array_push($months, $value[1] . '-' . $year, $value[3] . '-' . $year, $value[4] . '-' . $year, $value[5] . '-' . $year, $value[6] . '-' . $year, $value[7] . '-' . $year, $value[8] . '-' . $nextYear, $value[9] . '-' . $nextYear, $value[10] . '-' . $nextYear . $value[11] . '-' . $nextYear, $value[12] . '-' . $nextYear, $value[13] . '-' . $nextYear);
            // }
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

        dd($directCost);
    }
}
