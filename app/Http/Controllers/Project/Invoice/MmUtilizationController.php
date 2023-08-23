<?php

namespace App\Http\Controllers\Project\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrPosition;
use App\Models\Hr\HrEmployee;
use App\Http\Requests\Project\Invoice\MmUtilizationStore;
use App\Models\Project\Invoice\PrMmUtilization;
use App\Models\Project\Invoice\Invoice;
use App\Exports\Project\Invoice\UtilizationExport;
use Excel;
use DB;
use DataTables;

class MmUtilizationController extends Controller
{
    public function index()
    {
        $positions = PrPosition::where('pr_detail_id', session('pr_detail_id'))->get();
        $employees = HrEmployee::select('id', 'first_name', 'last_name', 'employee_no')->get();
        // invoice_type_1 is salary cost
        $invoices = Invoice::where('pr_detail_id', session('pr_detail_id'))->where('invoice_type_id', 1)->orderBy('invoice_no', 'desc')->get();
        $difference = $this->calculateDifferenceBetweenInvoiceUtilization($invoices);
        $view =  view('project.mmUtilization.create', compact('positions', 'employees', 'invoices', 'difference'))->render();
        return response()->json($view);
    }

    public function calculateDifferenceBetweenInvoiceUtilization($invoices)
    {
        $data = [];
        if ($invoices) {
            foreach ($invoices as $value) {
                $v['month'] = $value->invoiceMonth->invoice_month;
                $v['cost'] = $value->invoiceCost->amount;
                $totalUtilization = 0.0;
                $utilizations = $value->prMMUtilization;
                foreach ($utilizations as $utilization) {
                    if ($v['month'] == $utilization->month_year) {
                        $totalUtilization += $utilization->billing_rate * $utilization->man_month;
                    }
                }
                $v['utilization'] = round($totalUtilization, 0);
                if ($v['utilization'] != $v['cost']) {
                    $v['difference'] = $v['utilization'] - $v['cost'];
                    array_push($data, $v);
                }
            }
        }
        return $data;
    }

    public function create()
    {
        $invoices = Invoice::where('pr_detail_id', session('pr_detail_id'))->where('invoice_type_id', 1)->orderBy('invoice_no', 'desc')->get();
        $difference = $this->calculateDifferenceBetweenInvoiceUtilization($invoices);
        $data =  PrMmUtilization::where('pr_detail_id', session('pr_detail_id'))->get();
        return DataTables::of($data)
            ->editColumn('hr_employee_id', function ($row) {

                return $row->hrEmployee->full_name;
            })
            ->editColumn('pr_position_id', function ($row) {

                return $row->hrDesignation->name ?? '';
            })
            ->editColumn('billing_rate', function ($row) {

                return addComma($row->billing_rate);
            })
            ->addColumn('total', function ($row) {
                return addComma($row->man_month * $row->billing_rate);
            })

            ->addColumn('edit', function ($row) {

                $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editMmUtilization">Edit</a>';
                return $btn;
            })
            ->addColumn('delete', function ($row) {


                $btn = ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteMmUtilization">Delete</a>';

                return $btn;
            })
            ->rawColumns(['edit', 'delete'])
            ->with('difference', $difference)
            ->make(true);
    }

    public function store(MmUtilizationStore $request)
    {
        $input = $request->all();
        $input['pr_detail_id'] = session('pr_detail_id');
        //$input['month_year'] = \Carbon\Carbon::parse($request->month_year)->format('Y-m-d');

        DB::transaction(function () use ($input, $request) {
            $input =  PrMmUtilization::updateOrCreate(
                ['id' => $request->utilization_id],
                $input
            );
        }); // end transcation


        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($id)
    {
        $data = PrMmUtilization::find($id);
        return response()->json($data);
    }



    public function destroy($id)
    {

        PrMmUtilization::findOrFail($id)->delete();

        return response()->json(['status' => 'OK', 'message' => 'Data Successfully Deleted']);
    }

    public function invoice($id)
    {
        $invoice = Invoice::find($id);
        return response()->json($invoice->invoiceMonth);
    }

    public function exportUtilization()
    {



        // $fileName = 'utilizations.xlsx';
        // return Excel::download(new UtilizationExport(14), $fileName);
    }

    public function exportView(Request $request)
    {

        $prDetailId = 14;
        $months = PrMmUtilization::where('pr_detail_id', $prDetailId)->select('month_year')->groupBy('month_year')->orderBy('month_year', 'asc')->pluck('month_year')->toArray();

        $employeeIds = PrMmUtilization::where('pr_detail_id', $prDetailId)->select('hr_employee_id')->groupBy('hr_employee_id')->pluck('hr_employee_id')->toArray();
        $prPositions = PrPosition::where('pr_detail_id', $prDetailId)->get();
        //$prPositions = PrMmUtilization::where('pr_detail_id', $prDetailId)->select('pr_position_id')->groupBy('pr_position_id')->get();
        $prMmUtilizations = PrMmUtilization::where('pr_detail_id', $prDetailId)->select('pr_position_id', 'hr_employee_id', 'month_year', 'man_month', 'billing_rate')->get();

        $heading = ['no', 'designation', 'total_man_month', 'employee_name'];
        foreach ($months as $month) {
            array_push($heading, $month);
        }

        $positionArray = [];
        foreach ($prPositions as $key => $position) {
            $employeeArray = [];
            $positionTotalMm = PrMmUtilization::where('pr_detail_id', $prDetailId)->where('pr_position_id', $position->id)->sum('man_month');
            $employeeIds = PrMmUtilization::where('pr_detail_id', $prDetailId)->where('pr_position_id', $position->id)->select('hr_employee_id')->groupBy('hr_employee_id')->pluck('hr_employee_id')->toArray();
            $total = PrMmUtilization::where('pr_detail_id', $prDetailId)->where('pr_position_id', $position->id)->select(DB::raw('sum(billing_rate * man_month) as total'))->first()->total;
            foreach ($employeeIds as $key => $employeeId) {
                $billingRates = PrMmUtilization::where('pr_detail_id', $prDetailId)->where('pr_position_id', $position->id)->where('hr_employee_id', $employeeId)->select('billing_rate')->groupBy('billing_rate')->pluck('billing_rate')->toArray();
                $employeeTotalMm = PrMmUtilization::where('pr_detail_id', $prDetailId)->where('pr_position_id', $position->id)->where('hr_employee_id', $employeeId)->sum('man_month');
                foreach ($billingRates as $key => $billingRate) {
                    $utilizations = PrMmUtilization::where('pr_detail_id', $prDetailId)->where('pr_position_id', $position->id)->where('hr_employee_id', $employeeId)->where('billing_rate', $billingRate)->get();
                    $employeeTotal = PrMmUtilization::where('pr_detail_id', $prDetailId)->where('pr_position_id', $position->id)->where('hr_employee_id', $employeeId)->where('billing_rate', $billingRate)->select(DB::raw('sum(billing_rate * man_month) as total'))->first()->total;
                    $employeeValue = ['employee_name' => employeeName($employeeId)];
                    foreach ($utilizations as $utilization) {
                        $employeeValue['pr_position_id'] = $position->id;
                        $employeeValue['position'] = $position->hrDesignation->name;
                        $employeeValue['nominated_person'] = $position->nominated_person;
                        $employeeValue['total_man_month'] =  $position->total_mm;
                        $employeeValue['designation'] = $utilization->hrDesignation->name;
                        $employeeValue['billing_rate'] = $utilization->billing_rate;
                        $employeeValue['employee_total'] = $employeeTotal;
                        $employeeValue['employee_total_mm'] = $employeeTotalMm;
                        $employeeValue['total_mm_utilized'] = $positionTotalMm;
                        $employeeValue['total'] = $total;

                        foreach ($months as $month) {
                            if ($month == $utilization->month_year) {
                                $employeeValue[$month] = $utilization->man_month;
                            } else {
                                if (!array_key_exists($month, $employeeValue)) {
                                    $employeeValue[$month] = '';
                                }
                            }
                        }
                    }
                    array_push($employeeArray, $employeeValue);
                }
            }
            // $positionValue = ['employee_value' => $employeeArray];
            array_push($positionArray, $employeeArray);
        }
        //dd($positionArray);
        return view('project/mmUtilization/report', compact('months', 'positionArray'));


        // $employeeArray = [];
        // foreach ($employeeIds as $key => $employeeId) {
        //     $utilizations = PrMmUtilization::where('pr_detail_id', $prDetailId)->where('hr_employee_id', $employeeId)->get();
        //     $employeeValue = ['employee_name' => employeeName($employeeId)];
        //     foreach ($utilizations as $utilization) {
        //         $employeeValue['pr_position_id'] = $utilization->pr_position_id;
        //         $employeeValue['designation'] = $utilization->hrDesignation->name;
        //         foreach ($months as $month) {
        //             if ($month == $utilization->month_year) {
        //                 $employeeValue[$month] = $utilization->man_month;
        //             } else {
        //                 if (!array_key_exists($month, $employeeValue)) {
        //                     $employeeValue[$month] = '';
        //                 }
        //             }
        //         }
        //     }


        //     array_push($employeeArray,  $employeeValue);
        // }

        // dd($employeeArray);
        // $positionArray = [];

        // foreach ($prPositions as $key => $position) {
        //     $utilizationArray = [];
        //     $utilizations = PrMmUtilization::where('pr_detail_id', $prDetailId)->where('pr_position_id', $position->id)->get();

        //     foreach ($utilizations as $utilization) {
        //         $utilizationValue = ['hr_employee_id' => $utilization->hr_employee_id, 'month' => $utilization->month_year, 'mm' => $utilization->man_month, 'billing_rate' => $utilization->billing_rate];
        //         array_push($utilizationArray, $utilizationValue);
        //     }
        //     $positionValue = ['pr_position_id' => $position->id, 'position' => $position->hrDesignation->name, 'total_man_month' => $position->total_mm];



        //     // foreach ($prMmUtilizations as $utilization) {
        //     //     foreach ($employeesIds as $employeeId) {
        //     //         if ($employeeId == $utilization->hr_employee_id) {
        //     //             echo $utilization->hrEmployee->full_name;
        //     //             echo '<br>';
        //     //         }
        //     //     }

        //     //     // foreach ($months as $month) {
        //     //     //     echo '--' . $month;
        //     //     // }
        //     // }
        //     array_push($positionArray, $positionValue);
        // }
        // dd($positionArray);
        // // if ($request->ajax()) {
        // //      $data = DataTables::of($positionArray); 
        // //      $data = $data ->addColumn('key', function ($row) {
        // //             return $row['pr_position_id'];
        // //         })
        // //         ->addColumn('billing_rate', function ($row) {
        // //             return 1000;
        // //         })
        // //         ->addColumn('employee_name', function ($row) {
        // //             return 'Sohail Afzal';
        // //         });
        // //         // foreach ($months as $month){
        // //         // $data = $data
        // //         //     ->addColumn("$month", function ($row) {
        // //         //         return 'testing';
        // //         //     });
        // //         // }

        // //        return $data->make(true);
        // // }
        // return view('project/mmUtilization/report', compact('months', 'positionArray'));

        // dd($positionArray);




        // // $data = [];
        // // foreach ($prPositions as $postion) {
        // //     foreach ($prMmUtilizations as $utilization) {
        // //         if ($postion->id == $utilization->pr_position_id) {
        // //             $value = ['pr_position_id' => $utilization->pr_position_id, 'month_year' => $utilization->month_year, 'man_month' => $utilization->man_month, 'billing_rate' => $utilization->billing_rate, 'employee_name' => $utilization->hrEmployee->full_name, 'hr_employee_id' => $utilization->hr_employee_id];
        // //             array_push($data, $value);
        // //         }
        // //     }
        // // }
        // // $data = collect($data);
        // // $table = [];



        // // $table = $data->map(function ($value, $key) use ($months) {
        // //     // echo $value['month_year'] . '<br>';
        // //     foreach ($months as $month) {
        // //         if ($month->month_year == $value['month_year']) {
        // //         }
        // //     }
        // //     // $keys = array_keys($value[''], $month->month_year);
        // //     // echo $month->month_year . '<br>';
        // //     //echo $value['month_year'] . '<br>';
        // //     // }
        // // });
        // // dd($data);


        // $data = [];
        // foreach ($prPositions as $key => $position) {
        //     $utilizations = PrMmUtilization::where('pr_detail_id', 14)->where('pr_position_id', $position->id)->select('month_year', 'man_month', 'billing_rate', 'hr_employee_id')->get();
        //     $data1 = [];
        //     foreach ($utilizations as $utilization) {
        //         $value = ['month_year' => $utilization->month_year, 'man_month' => $utilization->man_month, 'billing_rate' => $utilization->billing_rate, 'employee_name' => $utilization->hrEmployee->full_name, 'hr_employee_id' => $utilization->hr_employee_id];
        //         array_push($data1, $value);
        //     }

        //     $v = array();

        //     foreach ($data1 as $d) {
        //         $v[] =  ['key' => $key + 1, 'pr_position_id' => $position->id, 'position_name' => $position->hrDesignation->name, 'total_man_month' => $position->total_mm, $d['month_year'] => $d['man_month'], 'billing_rate' => $d['billing_rate'], 'employee_name' => $d['employee_name']];
        //     }

        //     array_push($data, array_merge(...$v));
        // }

        // $data = collect($data);
        // dd($data);
        // //if ($request->ajax()) {
        // return DataTables::of($data)->make(true);
        // //}

        // // foreach ($prPositions as $position) {

        // //     foreach ($position->prMmUtilizations as $utilization) {
        // //         echo $utilization->hrEmployee->full_name . '<br>';
        // //     }

        // //     // foreach ($prMmUtilizations as $prMmUtilization) {
        // //     //     if ($position->pr_position_id == $prMmUtilization->pr_position_id) {
        // //     //         echo $prMmUtilization->hrEmployee->full_name . '-' . $prMmUtilization->hrDesignation->name . '<br>';
        // //     //     }
        // //     // }
        // // }
        // // dd();
        // // foreach ($months as $month) {
        // //     echo $month->month_year . '<br>';
        // // }
        // return view('project/mmUtilization/report', compact('months'));
    }
}
