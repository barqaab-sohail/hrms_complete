<?php

namespace App\Http\Controllers\Project;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Arr;
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
                // ->addColumn('Edit', function ($row) {

                //     $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editExpense">Edit</a>';

                //     return $btn;
                // })
                // ->addColumn('Delete', function ($row) {

                //     $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteExpense">Delete</a>';

                //     return $btn;
                // })
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
                ->editColumn('revenue', function ($row) {

                    return addComma($row->revenue ?? '');
                })
                ->editColumn('total_expense', function ($row) {

                    return addComma($row->salary_expense + $row->non_salary_expense + $row->non_reimbursable_salary + $row->non_reimbursable_expense);
                })
                ->rawColumns(['total_expense'])
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


    public function importExpense()
    {

        $prDetailId = session('pr_detail_id');
        $importRecord = 0;
        $updateRecord = 0;
        $years = calculateSyncYears($prDetailId);
        $prDetail = PrDetail::find($prDetailId);
        $projectNo = $prDetail->project_no;
        // return response()->json(['error' => 'Testing from start', 'years' => implode(",", $years), 'projectNo' => $projectNo]);
        foreach ($years as $year) {
            $data = $this->loadHtmlFile($projectNo, $year);
            if ($data['projectNo'] !=  $prDetail->project_no) {
                return response()->json(['error' => 'Project No is not match with this file']);
            } else if ($data['reportName'] != "Project Wise Income Statement" && $data['reportName'] != "Project Wise Income Statements") {
                return response()->json(['error' => 'Report is not match with this file']);
            } else {
                foreach ($data['months'] as $key => $value) {
                    $date = \Carbon\Carbon::parse($data['months'][$key])->format('Y-m-d');
                    // return response()->json(['error' => 'Testing from start', 'date' => $date, 'year' => $year, 'projectNo' => $data['months'][$key]]);
                    // break;
                    $prMonthlyExpense = PrMonthlyExpense::where('pr_detail_id', session('pr_detail_id'))->where('month', $date)->first();
                    if ($prMonthlyExpense) {
                        if ($prMonthlyExpense->salary_expense != $data['salaries'][$key] || $prMonthlyExpense->non_salary_expense != $data['expenses'][$key] || $prMonthlyExpense->non_reimbursable_salary != $data['nonRSalaries'][$key] || $prMonthlyExpense->non_reimbursable_expense != $data['nonRExpenses'][$key] || $prMonthlyExpense->revenue != $data['revenue'][$key]) {
                            ++$updateRecord;
                            $prMonthlyExpense->update(
                                [
                                    'month' => $date,
                                    'salary_expense' => $data['salaries'][$key],
                                    'non_salary_expense' => $data['expenses'][$key],
                                    'non_reimbursable_salary' => $data['nonRSalaries'][$key],
                                    'non_reimbursable_expense' => $data['nonRExpenses'][$key],
                                    'revenue' => $data['revenue'][$key]
                                ]
                            );
                        }
                    } else {
                        //check if all four values are null than not enter value
                        if (!($data['salaries'][$key] == null && $data['expenses'][$key] == null && $data['nonRSalaries'][$key] == null && $data['nonRExpenses'][$key] == null)) {
                            PrMonthlyExpense::create([
                                'pr_detail_id' => session('pr_detail_id'),
                                'month' =>  $date,
                                'salary_expense' => $data['salaries'][$key],
                                'non_salary_expense' => $data['expenses'][$key],
                                'non_reimbursable_salary' => $data['nonRSalaries'][$key],
                                'non_reimbursable_expense' => $data['nonRExpenses'][$key],
                                'revenue' => $data['revenue'][$key]
                            ]);
                            ++$importRecord;
                        }
                    }
                }
            }
        }

        $yearList = implode(",", $years);
        if ($importRecord == 0 && $updateRecord == 0) {
            return response()->json(['error' => "All Expenses are already Updated"]);
        } else {
            return response()->json(['success' => "$importRecord Record Sucessfully Entered and $updateRecord Record Updates for the years $yearList"]);
        }
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
        $revenueFromOperationKey = 0;
        $yearKey = '';
        $nextYear = '';
        $revenue = [];
        $months = [];
        $projectNoKey = '';
        $salaries = [];
        $nonRSalaries = [];
        $expenses = [];
        $nonRExpenses = [];

        $reportName = '';
        $data = collect();
        foreach ($aDataTableDetailHTML[0] as $key => $value) {
            $data->push($value);
            if ($value == "Project Wise Income Statement") {
                $reportName = $value;
            }
            if ($value == "REVENUE FROM  OPERATIONS") {
                $revenueFromOperationKey = $key;
            }
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
                $projectNoKey  = $key + 2;
            }
        }
        $year = '20' . $aDataTableDetailHTML[0][$yearKey];
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
        foreach ($twoArray as $val) {
            array_push($revenue, intval(str_replace(',', '', $aDataTableDetailHTML[0][($revenueFromOperationKey  + $val)])));
        }
        $projectNo = substr($aDataTableDetailHTML[0][$projectNoKey], -4);
        $data = ['months' => $months, 'projectNo' => $projectNo, 'salaries' => $salaries, 'expenses' => $expenses, 'nonRSalaries' => $nonRSalaries, 'nonRExpenses' => $nonRExpenses, 'reportName' => $reportName, 'revenue' => $revenue];
        return $data;
    }

    public function CustomerLedgerActivity()
    {
        $htmlContent = file_get_contents("C:\Users\Sohail Afzal\OneDrive\Desktop\\test2.html");
        $htmlContent = str_replace("&nbsp;", " ", $htmlContent);
        $htmlContent = str_replace("&amp;", "__saperator", $htmlContent);
        $htmlContent = strip_tags($htmlContent, ['td']);
        $DOM = new \DOMDocument();
        $DOM->loadHTML($htmlContent);

        //dd($DOM);
        $Detail = $DOM->getElementsByTagName('td');
        $data = collect();
        foreach ($Detail as $sNodeDetail) {
            if (trim($sNodeDetail->textContent != '')) {
                //$aDataTableDetailHTML[0][] = trim($sNodeDetail->textContent);
                $data = $data->push(trim($sNodeDetail->textContent));
            }
        }
        //dd($data);
        $page1 = [];
        $page2 = [];
        $page3 = [];
        $page4 = [];
        $count = 0;

        foreach ($data as $key => $value) {
            if ($value == 'Customer Code:') {
                $count++;
            }
            if ($count == 1) {
                if ($value == "Customer's Ledger Activity") {
                    // continue;
                }
                array_push($page1, $value);
            }
            if ($count == 2) {
                array_push($page2, $value);
            }
            if ($count == 3) {
                array_push($page3, $value);
            }
            if ($count == 4) {
                array_push($page4, $value);
            }
        }
        dd($page1);

        $clear = array_slice($page1, 7);
        // dd($clear);
        dd(array_chunk($clear, 9));
        dd($groups);
        dd($page1, $page2, $page3, $page4);
        $pageEndKeys = $data->filter(function ($value) {
            return $value == "Opening:";
        });
        $keys = $pageEndKeys->keys();

        $pages = $data->filter(function ($key, $value) use ($page1, $keys) {
            if ($key < $keys[0]) {
                return  $page1 = $page1->push($value);
            }
        });
        dd($pages);
        //dd($keys[0]);
        foreach ($data as $key => $value) {
            if ($keys->has(0) && $key < $keys[0]) {
                $page1 = $page1->push($value);
            } else if ($keys->has(1) && $key < $keys[1]) {
                $page2 = $page2->push($value);
            } else if ($keys->has(2) && $key < $keys[2]) {
                $page3 = $page3->push($value);
            } else if ($keys->has(3) && $key < $keys[3]) {
                $page4 = $page4->push($value);
            } else {
                break;
            }
        }

        dd($page1, $page2, $page3);

        //dd($data);
        //whieh "Customer's Ledger Activity" new array
        //dd($aDataTableDetailHTML[0]);
        //$dataC = collect($aDataTableDetailHTML[0]);

        dd($keys);
        //dd($aDataTableDetailHTML[0]);
        $customerName = array_search('Customer Name:', $aDataTableDetailHTML[0]);

        // dd($customerName);
        $customerNameKey = [];
        $dataKey = '';
        $customerCodeKey = '';
        foreach ($aDataTableDetailHTML[0] as $key => $value) {
            if ($value == 'Customer Name:') {
                array_push($customerNameKey,  $key + 1);
                $dataKey = $key;
            }
        }

        //dd($customerNameKey);
        $data = [];
        for ($i = 0; $i < count($aDataTableDetailHTML[0]); $i++) {
            array_push($data, $aDataTableDetailHTML[0][$i]);
            //echo $aDataTableDetailHTML[0][$i] . '<br>';
        }

        dd(array_chunk($data, 363));
        dd($aDataTableDetailHTML[0][$customerNameKey]);
    }
}
