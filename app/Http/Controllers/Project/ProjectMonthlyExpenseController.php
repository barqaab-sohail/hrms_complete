<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrMonthlyExpense;
use App\Http\Requests\Project\PrMonthlyExpenseStore;
use App\Models\Project\Invoice\Invoice;
use App\Models\Project\Invoice\InvoiceCost;
use App\Models\Project\Payment\PaymentReceive;
use DB;
use DataTables;


class ProjectMonthlyExpenseController extends Controller
{
    public function index()
    {

        $totalExpenses = addComma(PrMonthlyExpense::where('pr_detail_id', session('pr_detail_id'))->sum('salary_expense') + PrMonthlyExpense::where('pr_detail_id', session('pr_detail_id'))->sum('non_salary_expense'));
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
            $data = PrMonthlyExpense::where('pr_detail_id', session('pr_detail_id'))->latest()->get();

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
                ->editColumn('total_expense', function ($row) {

                    return addComma($row->salary_expense + $row->non_salary_expense);
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
}
