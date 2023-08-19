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
        
        $months = ["October-2020",  "November-2020",  "December-2020",  "January-2021",  "February-2021",  "March-2021",  "April-2021",  "May-2021",  "June-2021",  "July-2021",  "August-2021",  "September-2021",  "October-2021",  "November-2021",  "December-2021",  "January-2022",  "February-2022",  "March-2022",  "April-2022",  "May-2022",  "June-2022",  "July-2022",  "August-2022",  "September-2022",  "October-2022",  "November-2022",  "December-2022",  "January-2023",  "February-2023",  "March-2023",  "April-2023",  "May-2023",  "June-2023"];
        $employeesIds = PrMmUtilization::where('pr_detail_id', $prDetailId)->select('hr_employee_id')->groupBy('hr_employee_id')->pluck('hr_employee_id')->toArray();
        $prPositions = PrPosition::where('pr_detail_id', $prDetailId)->get();
        //$prPositions = PrMmUtilization::where('pr_detail_id', $prDetailId)->select('pr_position_id')->groupBy('pr_position_id')->get();
        $prMmUtilizations = PrMmUtilization::where('pr_detail_id', $prDetailId)->select('pr_position_id', 'hr_employee_id', 'month_year', 'man_month', 'billing_rate')->get();

        $heading = ['no', 'designation', 'total_man_month', 'employee_name'];
        foreach ($months as $month) {
            array_push($heading, $month);
        }



        $positionArray = [];

        foreach ($prPositions as $key => $position) {
            $utilizationArray = [];
            $utilizations = PrMmUtilization::where('pr_detail_id', $prDetailId)->where('pr_position_id', $position->id)->get();

            foreach ($utilizations as $utilization) {
                $utilizationValue = ['hr_employee_id' => $utilization->hr_employee_id, 'month' => $utilization->month_year, 'mm' => $utilization->man_month, 'billing_rate' => $utilization->billing_rate];
                array_push($utilizationArray, $utilizationValue);
            }
            $positionValue = ['pr_position_id' => $position->id, 'position' => $position->hrDesignation->name, 'total_man_month' => $position->total_mm];
            foreach ($months as $month) {
                $positionValue[$month] ='';
            }


            // foreach ($prMmUtilizations as $utilization) {
            //     foreach ($employeesIds as $employeeId) {
            //         if ($employeeId == $utilization->hr_employee_id) {
            //             echo $utilization->hrEmployee->full_name;
            //             echo '<br>';
            //         }
            //     }

            //     // foreach ($months as $month) {
            //     //     echo '--' . $month;
            //     // }
            // }
            array_push($positionArray, $positionValue);
        }
        if ($request->ajax()) {
             $data = DataTables::of($positionArray); 
             $data = $data ->addColumn('key', function ($row) {
                    return $row['pr_position_id'];
                })
                ->addColumn('billing_rate', function ($row) {
                    return 1000;
                })
                ->addColumn('employee_name', function ($row) {
                    return 'Sohail Afzal';
                });
                // foreach ($months as $month){
                // $data = $data
                //     ->addColumn("$month", function ($row) {
                //         return 'testing';
                //     });
                // }
               
               return $data->make(true);
        }
        return view('project/mmUtilization/report', compact('months'));

        dd($positionArray);




        // $data = [];
        // foreach ($prPositions as $postion) {
        //     foreach ($prMmUtilizations as $utilization) {
        //         if ($postion->id == $utilization->pr_position_id) {
        //             $value = ['pr_position_id' => $utilization->pr_position_id, 'month_year' => $utilization->month_year, 'man_month' => $utilization->man_month, 'billing_rate' => $utilization->billing_rate, 'employee_name' => $utilization->hrEmployee->full_name, 'hr_employee_id' => $utilization->hr_employee_id];
        //             array_push($data, $value);
        //         }
        //     }
        // }
        // $data = collect($data);
        // $table = [];



        // $table = $data->map(function ($value, $key) use ($months) {
        //     // echo $value['month_year'] . '<br>';
        //     foreach ($months as $month) {
        //         if ($month->month_year == $value['month_year']) {
        //         }
        //     }
        //     // $keys = array_keys($value[''], $month->month_year);
        //     // echo $month->month_year . '<br>';
        //     //echo $value['month_year'] . '<br>';
        //     // }
        // });
        // dd($data);


        $data = [];
        foreach ($prPositions as $key => $position) {
            $utilizations = PrMmUtilization::where('pr_detail_id', 14)->where('pr_position_id', $position->id)->select('month_year', 'man_month', 'billing_rate', 'hr_employee_id')->get();
            $data1 = [];
            foreach ($utilizations as $utilization) {
                $value = ['month_year' => $utilization->month_year, 'man_month' => $utilization->man_month, 'billing_rate' => $utilization->billing_rate, 'employee_name' => $utilization->hrEmployee->full_name, 'hr_employee_id' => $utilization->hr_employee_id];
                array_push($data1, $value);
            }

            $v = array();

            foreach ($data1 as $d) {
                $v[] =  ['key' => $key + 1, 'pr_position_id' => $position->id, 'position_name' => $position->hrDesignation->name, 'total_man_month' => $position->total_mm, $d['month_year'] => $d['man_month'], 'billing_rate' => $d['billing_rate'], 'employee_name' => $d['employee_name']];
            }

            array_push($data, array_merge(...$v));
        }

        $data = collect($data);
        dd($data);
        //if ($request->ajax()) {
        return DataTables::of($data)->make(true);
        //}

        // foreach ($prPositions as $position) {

        //     foreach ($position->prMmUtilizations as $utilization) {
        //         echo $utilization->hrEmployee->full_name . '<br>';
        //     }

        //     // foreach ($prMmUtilizations as $prMmUtilization) {
        //     //     if ($position->pr_position_id == $prMmUtilization->pr_position_id) {
        //     //         echo $prMmUtilization->hrEmployee->full_name . '-' . $prMmUtilization->hrDesignation->name . '<br>';
        //     //     }
        //     // }
        // }
        // dd();
        // foreach ($months as $month) {
        //     echo $month->month_year . '<br>';
        // }
        return view('project/mmUtilization/report', compact('months'));
    }
}
