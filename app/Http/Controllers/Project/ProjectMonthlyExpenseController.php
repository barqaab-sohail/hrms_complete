<?php

namespace App\Http\Controllers\Project;

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Project\PrMonthlyExpense;
use App\Http\Requests\Project\PrMonthlyExpenseStore;
use App\Models\Project\Invoice\Invoice;
use App\Models\Project\PrDetail;
use App\Models\Project\Invoice\InvoiceCost;
use App\Models\Project\Payment\PaymentReceive;
use DB;
use Excel;
use App\Imports\Project\ExpenseImport;
use DataTables;


class ProjectMonthlyExpenseController extends Controller
{
    public function index()
    {

        $totalExpenses = addComma(PrMonthlyExpense::where('pr_detail_id', session('pr_detail_id'))->sum('salary_expense') + PrMonthlyExpense::where('pr_detail_id', session('pr_detail_id'))->sum('non_salary_expense') + PrMonthlyExpense::where('pr_detail_id', session('pr_detail_id'))->sum('non_reimbursable_salary') + PrMonthlyExpense::where('pr_detail_id', session('pr_detail_id'))->sum('non_reimbursable_expense'));
        $totalReceived = addComma(PaymentReceive::where('pr_detail_id', session('pr_detail_id'))->sum('amount'));
        //$invoiceIds = Invoice::where('pr_detail_id', session('pr_detail_id'))->pluck('id')->toArray();
        $invoiceReceivedIds = PaymentReceive::where('pr_detail_id', session('pr_detail_id'))->pluck('invoice_id')->toArray();
        $pendingInvoiceIds = Invoice::where('pr_detail_id', session('pr_detail_id'))->whereNotIn('id',  $invoiceReceivedIds)->pluck('id')->toArray();
        $pendingInvoicesWOSTax = addComma(InvoiceCost::whereIn('invoice_id', $pendingInvoiceIds)->sum('amount'));
        $view =  view('project.expense.create', compact('totalExpenses', 'totalReceived', 'pendingInvoicesWOSTax'))->render();
        return response()->json($view);
    }

    public function create(Request $request)
    {

        if ($request->ajax()) {
            $data = PrMonthlyExpense::where('pr_detail_id', session('pr_detail_id'))->orderBy('month', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('Edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editExpense">Edit</a>';

                    return $btn;
                })
                ->addColumn('Delete', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteExpense">Delete</a>';


                    return $btn;
                })
                ->editColumn('month', function ($row) {

                    return \Carbon\Carbon::parse($row->month)->format('Y-M');
                })
                ->editColumn('salary_expense', function ($row) {

                    return addComma($row->salary_expense ?? '');
                })
                ->editColumn('non_salary_expense', function ($row) {

                    return addComma($row->non_salary_expense ?? '');
                })
                ->editColumn('non_reimbursable_salary', function ($row) {

                    return addComma($row->non_reimbursable_salary ?? '');
                })
                ->editColumn('non_reimbursable_expense', function ($row) {

                    return addComma($row->non_reimbursable_expense ?? '');
                })
                ->editColumn('total_expense', function ($row) {

                    return addComma($row->salary_expense + $row->non_salary_expense + $row->non_reimbursable_salary + $row->non_reimbursable_expense);
                })
                ->rawColumns(['Edit', 'Delete', 'total_expense'])
                ->make(true);
        }
    }

    public function store(PrMonthlyExpenseStore $request)
    {

        $input = $request->all();

        if ($request->filled('salary_expense')) {
            $input['salary_expense'] = intval(str_replace(',', '', $request->salary_expense));
        }

        if ($request->filled('non_salary_expense')) {
            $input['non_salary_expense'] = intval(str_replace(',', '', $request->non_salary_expense));
        }

        DB::transaction(function () use ($input, $request) {

            PrMonthlyExpense::updateOrCreate(['id' => $input['expense_id']], $input);
        }); // end transcation

        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {
        $prMonthlyExpense = PrMonthlyExpense::find($id);
        return response()->json($prMonthlyExpense);
    }

    public function destroy($id)
    {

        PrMonthlyExpense::findOrFail($id)->delete();
        return response()->json(['status' => 'OK', 'message' => "Data Successfully Deleted"]);
    }


    public function importExpense(Request $request)
    {


        $importRecord = 0;
        $updateRecord = 0;
        // $this->validate($request, [
        //     'excel_file'  => 'required|file|max:15|mimes:xls,xlsx'
        // ], [
        //     'required' => 'Excel File Required ',
        //     'mimes' => 'Only Excel File Accepted'
        // ]);
        $prDetail = PrDetail::find(43);
        $projectNo = $prDetail->project_no;
        $year =  \Carbon\Carbon::parse($prDetail->commencement_date)->format('Y');
        $path1 = $request->file('excel_file')->store('temp');
        $path = storage_path('app') . '/' . $path1;
        $data = $this->loadHtmlFile($projectNo, $year);
        dd($data);



        // $prDetail = PrDetail::find(session('pr_detail_id'));
        // $import = new ExpenseImport();
        // Excel::import($import, $path);

        // if ($import->data['projectNo'] !=  $prDetail->project_no) {
        //     return response()->json(['error' => 'Project No is not match with this file']);
        // } else if ($import->data['reportName'] != "Project Wise Income Statement" && $import->data['reportName'] != "Project Wise Income Statements") {
        //     return response()->json(['error' => 'Report is not match with this file']);
        // } else if (!$import->data['isColumnTwoEmpty']) {
        //     return response()->json(['error' => 'Report Format is not match against column 2']);
        // } else {
        //     foreach ($import->data['months'] as $key => $value) {
        //         $date = \Carbon\Carbon::parse($import->data['months'][$key])->format('Y-m-d');
        //         $prMonthlyExpense = PrMonthlyExpense::where('pr_detail_id', session('pr_detail_id'))->where('month', $date)->first();
        //         if ($prMonthlyExpense) {
        //             if ($prMonthlyExpense->salary_expense != $import->data['salary'][$key] || $prMonthlyExpense->non_salary_expense != $import->data['expense'][$key] || $prMonthlyExpense->non_reimbursable_salary != $import->data['non_reimbursable_salary'][$key] || $prMonthlyExpense->non_reimbursable_expense != $import->data['non_reimbursable_expense'][$key]) {
        //                 ++$updateRecord;
        //                 $prMonthlyExpense->update(
        //                     [
        //                         'salary_expense' => $import->data['salary'][$key],
        //                         'non_salary_expense' => $import->data['expense'][$key],
        //                         'non_reimbursable_salary' => $import->data['non_reimbursable_salary'][$key],
        //                         'non_reimbursable_expense' => $import->data['non_reimbursable_expense'][$key]
        //                     ]
        //                 );
        //             }
        //         } else {
        //             //check if all four values are null than not enter value
        //             if (!($import->data['salary'][$key] == null && $import->data['expense'][$key] == null && $import->data['non_reimbursable_salary'][$key] == null && $import->data['non_reimbursable_expense'][$key] == null)) {
        //                 PrMonthlyExpense::create([
        //                     'pr_detail_id' => session('pr_detail_id'),
        //                     'month' =>  $date,
        //                     'salary_expense' => $import->data['salary'][$key],
        //                     'non_salary_expense' => $import->data['expense'][$key],
        //                     'non_reimbursable_salary' => $import->data['non_reimbursable_salary'][$key],
        //                     'non_reimbursable_expense' => $import->data['non_reimbursable_expense'][$key]
        //                 ]);
        //                 ++$importRecord;
        //             }
        //         }
        //     }
        // }
        // if (File::exists($path)) {
        //     File::delete($path);
        // }
        // if ($importRecord == 0 && $updateRecord == 0) {
        //     return response()->json(['error' => "All Record is Already Updated"]);
        // } else {
        //     return response()->json(['success' => "$importRecord Record Sucessfully Entered and $updateRecord Record Updates"]);
        // }
    }


    public function loadHtmlFile($projectNo, $year)
    {
        $url = "http://194.116.228.8:8888/reports/rwservlet?userid=BARQAAB/BARQAAB@scar&domain=classicdomain&report=D:\app\SYSTEM\BARQAAB\REPORTS\FR_PROJ_12MONTHS_FIN_SMRY&destype=CACHE&desformat=HTML&paramform=no&PPCD=__YEAR&PMCD=07&PMNODE=06FS30636&PUNCD=0001&PSTNATURE=01&PENNATURE=02&PSTREGION=01&PENREGION=05&PSTPROVINCE=01&PENPROVINCE=06&PSTCD=01__PROJECTNO&PENCD=012086&PSTDT=&PENDT=&PSTUC=0001&PENUC=9999&PUNCD=0001&PSTNATURE=01&PENNATURE=02&PSTREGION=01&PENREGION=05&PSTPROVINCE=01&PENPROVINCE=06&PSTVT=AAA&PENVT=ZZZ&PVST=&PPST=";
        $url = str_replace("__YEAR", substr($year, -2), $url);
        $url = str_replace("__PROJECTNO", $projectNo, $url);

        $htmlContent = file_get_contents($url);
        $htmlContent = str_replace("&nbsp;", " ", $htmlContent);
        $htmlContent = strip_tags($htmlContent, ['td']);
        $DOM = new \DOMDocument();
        $DOM->loadHTML($htmlContent);

        $Detail = $DOM->getElementsByTagName('td');

        foreach ($Detail as $sNodeDetail) {
            $aDataTableDetailHTML[0][] = trim($sNodeDetail->textContent);
        }
        $reimbursementSalaryKey = 0;
        $reimbursementExpensesKey = 0;
        $nonReimbursementExpensesKey = 0;
        $nonReimbursementSalaryKey = 0;
        $yearKey = '';
        $nextYear = '';
        $months = [];
        $projectNo = '';
        $salaries = [];
        $nonRSalaries = [];
        $expenses = [];
        $nonRExpenses = [];
        $data = collect();
        foreach ($aDataTableDetailHTML[0] as $key => $value) {
            $data->push($value);
            if ($value == "REIMBURSEABLE SALARIE") {
                $reimbursementSalaryKey = $key;
            }
            if ($value == "REIMBURSEABLE EXPENSE") {
                $reimbursementExpensesKey = $key;
            }
            if ($value == "NON REMIBURSABLE EXPE") {
                $nonReimbursementExpensesKey = $key;
            }
            if ($value == "NON REIMBURSEABLESAL") {
                $nonReimbursementSalaryKey  = $key;
            }
            if ($value == "For The Year") {
                $yearKey  = $key + 2;
            }
            if ($value == "Project Code and Name:") {
                $projectNo  = $key + 2;
            }
        }
        $year = $aDataTableDetailHTML[0][$yearKey];
        $nextYear = $year + 1;
        array_push($months, 'Jul' . '-' . $year, 'Aug' . '-' . $year, 'Sep' . '-' . $year, 'Oct' . '-' . $year, 'Nov' . '-' . $year, 'Dec' . '-' . $year, 'Jan' . '-' . $nextYear, 'Feb' . '-' . $nextYear, 'Mar' . '-' . $nextYear, 'Apr' . '-' . $nextYear, 'May' . '-' . $nextYear, 'Jun' . '-' . $nextYear);
        $twoArray = [2, 4, 6, 8, 10, 12, 14, 16, 18, 20, 22, 24];
        foreach ($twoArray as $val) {
            array_push($salaries, intval(str_replace(',', '', $aDataTableDetailHTML[0][($reimbursementSalaryKey + $val)])));
        }
        foreach ($twoArray as $val) {
            array_push($expenses, intval(str_replace(',', '', $aDataTableDetailHTML[0][($reimbursementExpensesKey + $val)])));
        }
        foreach ($twoArray as $val) {
            array_push($nonRSalaries, intval(str_replace(',', '', $aDataTableDetailHTML[0][($nonReimbursementSalaryKey + $val)])));
        }
        foreach ($twoArray as $val) {
            array_push($nonRExpenses, intval(str_replace(',', '', $aDataTableDetailHTML[0][($nonReimbursementExpensesKey  + $val)])));
        }

        $data = ['months' => $months, 'projectNo' => $projectNo, 'salaries' => $salaries, 'expenses' => $expenses, 'nonRSalaries' => $nonRSalaries, 'nonRExpenses' => $nonRExpenses];
        dd($data);
        //return $keyvalue;
    }


    public function convertPdfToExcel($path, $outPut)
    {
        $className = \PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf::class;
        IOFactory::registerWriter('Pdf', $className);
        IOFactory::registerReader('Pdf', $className);


        $inputFileType = 'PDF';
        $inputFileName = $path;
        $outputFileType = 'Xlsx';
        $outputFileName = $outPut;

        $reader = IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($inputFileName);

        $writer = IOFactory::createWriter($spreadsheet, $outputFileType);
        $writer->save($outputFileName);

        return $outputFileName;

        // $inputFileType = 'PDF';
        // $inputFileName = 'path/to/your/pdf/file.pdf';
        // $outputFileType = 'Xlsx';
        // $outputFileName = 'path/to/your/excel/file.xlsx';

        // $reader = IOFactory::createReader($inputFileType);
        // $spreadsheet = $reader->load($inputFileName);

        // $writer = IOFactory::createWriter($spreadsheet, $outputFileType);
        // $writer->save($outputFileName);
    }
}
