<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrDetail;
use App\Models\Project\LedgerActivity;
use DB;
use DataTables;

class ProjectLedgerActivityController extends Controller
{
    public function show($prDetailId)
    {

        $prDetail = PrDetail::find($prDetailId);
        $view =  view('project.ledgerActivity.create', compact('prDetail'))->render();
        return response()->json($view);
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            $data = LedgerActivity::where('pr_detail_id', $request->prDetailId)->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('debit', function ($row) {

                    return addComma($row->debit ?? '');
                })
                ->editColumn('credit', function ($row) {

                    return addComma($row->credit ?? '');
                })
                ->editColumn('balance', function ($row) {

                    return addComma($row->balance ?? '');
                })
                ->make(true);
        }

        $view =  view('project.ledgerActivity.create')->render();
        return response()->json($view);
    }


    public function importLedgerActivity($prDetailId)
    {


        $prDetail = PrDetail::find($prDetailId);
        $customerNo = $prDetail->prCustomerNo->customer_no ?? '';
        $projectNo = $prDetail->project_no;
        if ($customerNo == '') {
            return response()->json(['error' => 'Cusomer No is not entered, please enter customer no first'], 400);
        }
        $toDate = \Carbon\Carbon::now()->format('d-M-y');
        $data = "";
        if ($customerNo != '' && $projectNo != '') {
            $data = $this->CustomerLedgerActivity($projectNo, $customerNo, $toDate);
        } else {
            $data = "Error for reading file";
        }

        $ledgerActivities = LedgerActivity::where('pr_detail_id', $prDetailId)->get();

        $databaseCount = $ledgerActivities->count();
        $dataCount = count($data['data']);

        if ($ledgerActivities->isEmpty()) {
            foreach ($data['data'] as $key => $value) {
                LedgerActivity::create([
                    'pr_detail_id' => $prDetailId,
                    'voucher_date' => \Carbon\Carbon::createFromFormat('d-m-y', $data['data'][$key][0])->format('Y-m-d'),
                    'voucher_no' => $value[1],
                    'reference_date' => $value[2] == '' ? null : \Carbon\Carbon::parse($value[2])->format('Y-m-d'),
                    'reference_no' => $value[3],
                    'description' => $value[4],
                    'debit' => intval(str_replace(',', '', $value[5])),
                    'credit' => intval(str_replace(',', '', $value[6])),
                    'balance' => intval(str_replace(',', '', $value[7])),
                    'remarks' => $value[8],

                ]);
            }
            $message = "Data Enter sucessfully";
        } else {
            if ($databaseCount >= $dataCount) {
                foreach ($ledgerActivities as $key => $ledgerActivity) {
                    if (isset($data['data'][$key])) {
                        $isData = true;
                    } else {
                        $isData = false;
                    }
                    if ($isData) {
                        $ledgerActivity->update([
                            'pr_detail_id' => $prDetailId,
                            'voucher_date' => \Carbon\Carbon::createFromFormat('d-m-y', $data['data'][$key][0])->format('Y-m-d'),
                            'voucher_no' => $data['data'][$key][1],
                            'reference_date' => $data['data'][$key][2] == '' ? null : \Carbon\Carbon::parse($data['data'][$key][2])->format('Y-m-d'),
                            'reference_no' => $data['data'][$key][3],
                            'description' => $data['data'][$key][4],
                            'debit' => intval(str_replace(',', '', $data['data'][$key][5])),
                            'credit' => intval(str_replace(',', '', $data['data'][$key][6])),
                            'balance' => intval(str_replace(',', '', $data['data'][$key][7])),
                            'remarks' => $data['data'][$key][8],
                        ]);
                    } else {
                        $ledgerActivity->delete();
                    }
                }
                $message = "Database sync sucessfully";
            } else {
                foreach ($data['data'] as $key => $value) {
                    if (isset($ledgerActivities[$key])) {
                        $id = $ledgerActivities[$key]['id'];
                    } else {
                        $id = null;
                    }

                    LedgerActivity::updateOrCreate(['id' => $id], [
                        'pr_detail_id' => $prDetailId,
                        'voucher_date' => \Carbon\Carbon::createFromFormat('d-m-y', $data['data'][$key][0])->format('Y-m-d'),
                        'voucher_no' => $data['data'][$key][1],
                        'reference_date' => $data['data'][$key][2] == '' ? null : \Carbon\Carbon::parse($data['data'][$key][2])->format('Y-m-d'),
                        'reference_no' => $data['data'][$key][3],
                        'description' => $data['data'][$key][4],
                        'debit' => intval(str_replace(',', '', $data['data'][$key][5])),
                        'credit' => intval(str_replace(',', '', $data['data'][$key][6])),
                        'balance' => intval(str_replace(',', '', $data['data'][$key][7])),
                        'remarks' => $data['data'][$key][8],
                    ]);
                }
                $message = "Data Updated sucessfully";
            }
        }

        return response()->json(['success' => $message], 200);
    }

    public function CustomerLedgerActivity($projectNo, $customerNo, $toDate)
    {

        $url = "http://79.110.232.23:8888/reports/rwservlet?userid=BARQAAB/BARQAAB@scar&domain=classicdomain&report=D:\app\SYSTEM\BARQAAB\REPORTS\AR_LGR&destype=CACHE&desformat=HTML&paramform=no&PPCD=22&PMNODE=12AR302013&PUNCD=__PROJECTNO&PSTCD=__CUSTOMERNO&PENCD=__CUSTOMERNO&PSTDT=30-JUN-19&PENDT=__TODATE&PSTUC=__PROJECTNO&PENUC=__PROJECTNO&PUNCD=__PROJECTNO&PSTVT=AAA&PENVT=ZZZ&PVST=&PPST=";
        $url = str_replace("__PROJECTNO", $projectNo, $url);
        $url = str_replace("__CUSTOMERNO", $customerNo, $url);
        $url = str_replace("__TODATE", $toDate, $url);

        $htmlContent = file_get_contents($url);

        //$htmlContent = file_get_contents("C:\Users\sohail\Desktop\\testt.htm");
        $htmlContent = str_replace("&nbsp;", " ", $htmlContent);
        //following line is not used, it is used for future if recorded 'a' tag
        $htmlContent = str_replace("&amp;", "__saperator", $htmlContent);
        $htmlContent = strip_tags($htmlContent, ['td']);
        $DOM = new \DOMDocument();
        $DOM->loadHTML($htmlContent);


        $Detail = $DOM->getElementsByTagName('td');

        foreach ($Detail as $sNodeDetail) {
            if (trim($sNodeDetail->textContent != '')) {
                $aDataTableDetailHTML[0][] = trim($sNodeDetail->textContent);
                //$data = $data->push(trim($sNodeDetail->textContent));
            }
        }
        $flag = false;
        $page1 = [];
        $customerCodeKey = array_search('Customer Code:', $aDataTableDetailHTML[0]);
        $customerCode = $aDataTableDetailHTML[0][$customerCodeKey + 1];

        foreach ($aDataTableDetailHTML[0] as $key => $value) {
            if ($value == 'Customer Code:') {
                $flag = true;
            }

            if ($value == "Customer's Ledger Activity") {
                $flag = false;
            }
            if ($value == "Customer Total:") {
                $flag = false;
            }

            if ($flag) {
                array_push($page1, $value);
            }
        }

        array_splice($page1, 0, 7);
        $result = array_search('Customer Code:', $page1);
        while ($result) {
            array_splice($page1, $result, $result + 7);
            $result = array_search('Customer Code:', $page1);
        }
        $chunkData = array_chunk($page1, 9);
        $finalResult = ['customer_no' => $customerCode, 'data' => $chunkData];
        // dd($finalResult);
        return $finalResult;
    }

    public function updateLedgerActivity()
    {
        // $url = "http://194.116.228.8:8888/reports/rwservlet?userid=BARQAAB/BARQAAB@scar&domain=classicdomain&report=D:\app\SYSTEM\BARQAAB\REPORTS\AR_LGR&destype=CACHE&desformat=HTML&paramform=no&PPCD=22&PMNODE=12AR302013&PUNCD=__PROJECTNO&PSTCD=__CUSTOMERNO&PENCD=__CUSTOMERNO&PSTDT=30-JUN-19&PENDT=__TODATE&PSTUC=__PROJECTNO&PENUC=__PROJECTNO &PUNCD=__PROJECTNO&PSTVT=AAA&PENVT=ZZZ&PVST=&PPST=";
        // $url = str_replace("__PROJECTNO", $projectNo, $url);
        // $url = str_replace("__CUSTOMERNO", $customerNo, $url);
        // $url = str_replace("__TODATE", $toDate, $url);
        // $htmlContent = file_get_contents($url);
        $htmlContent = file_get_contents("C:\Users\Sohail Afzal\OneDrive\Desktop\Reduced\\test_short.html");
        $htmlContent = str_replace("&nbsp;", " ", $htmlContent);
        //following line is not used, it is used for future if recorded 'a' tag
        $htmlContent = str_replace("&amp;", "__saperator", $htmlContent);
        $htmlContent = strip_tags($htmlContent, ['td']);
        $DOM = new \DOMDocument();
        $DOM->loadHTML($htmlContent);


        $Detail = $DOM->getElementsByTagName('td');

        foreach ($Detail as $sNodeDetail) {
            if (trim($sNodeDetail->textContent != '')) {
                $aDataTableDetailHTML[0][] = trim($sNodeDetail->textContent);
            }
        }
        $flag = false;
        $customerCodeKeys = [];
        $page1 = [];

        foreach ($aDataTableDetailHTML[0] as $key => $value) {
            if ($value == "Customer Code:") {
                $flag = true;
            }

            if ($value == "Customer Total:") {
                $flag = false;
            }

            if ($flag) {
                array_push($page1, $value);
            }
        }

        foreach ($page1 as $key => $value) {
            if ($value == "Customer Code:") {
                array_push($customerCodeKeys, $key);
            }
        }

        foreach ($customerCodeKeys as $key => $value) {
            unset($page1[$value]);
            unset($page1[$value + 1]);
            unset($page1[$value + 2]);
            unset($page1[$value + 3]);
            unset($page1[$value + 4]);
            unset($page1[$value + 5]);
            unset($page1[$value + 6]);
        }

        $chunkData = array_chunk($page1, 9);

        $receivedPayments = [];
        foreach ($chunkData as $key => $value) {
            foreach ($value as $innerKey => $innerValue) {
                if ($innerKey == 2 && $innerValue != '') {

                    array_push($receivedPayments, \Carbon\Carbon::parse($innerValue)->format('d-M-y'));
                }
                if ($innerKey == 6 && $innerValue != '.00') {
                    array_push($receivedPayments, $innerValue);
                }
            }
        }

        $paymentData = array_chunk($receivedPayments, 2);
        dd($paymentData);
        foreach ($paymentData as $key => $value) {
            echo $key . '--' . $value[0] . '<br>';
            echo $key . '--' . $value[1] . '<br>';
            echo '<br>';
        }
        dd();
        print_r($paymentData);
    }
}
