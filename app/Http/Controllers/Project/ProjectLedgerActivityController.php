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
    public function index()
    {

        $view =  view('project.ledgerActivity.create')->render();
        return response()->json($view);
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            $data = LedgerActivity::where('pr_detail_id', session('pr_detail_id'))->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        $view =  view('project.ledgerActivity.create')->render();
        return response()->json($view);
    }


    public function importLedgerActivity()
    {
        return response()->json(['error' => 'Customer No is not found']);
    }

    public function CustomerLedgerActivity($projectNo, $customerNo)
    {
        //$htmlContent = file_get_contents("C:\Users\Sohail Afzal\OneDrive\Desktop\Reduced\\test2.html");
        $url = "http://194.116.228.8:8888/reports/rwservlet?userid=BARQAAB/BARQAAB@scar&domain=classicdomain&report=D:\app\SYSTEM\BARQAAB\REPORTS\AR_LGR&destype=CACHE&desformat=HTML&paramform=no&PPCD=22&PMNODE=12AR302013&PUNCD=__PROJECTNO&PSTCD=__CUSTOMERNO&PENCD=&PSTDT=30-JUN-19&PENDT=05-MAY-23&PSTUC=__PROJECTNO&PENUC=9999&PUNCD=__PROJECTNO&PSTVT=AAA&PENVT=ZZZ&PVST=&PPST=";
        $url = str_replace("__PROJECTNO", $projectNo, $url);
        $url = str_replace("__CUSTOMERNO", $customerNo, $url);

        $htmlContent = file_get_contents($url);
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
}
