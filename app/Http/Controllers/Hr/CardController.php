<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use App\Models\Hr\HrEmployee;
use Illuminate\Support\Str;
use DNS1D;

class CardController extends Controller
{


    public function getEmployeePicture($employeeId)
    {
        $employee = HrEmployee::find($employeeId);
        $picture =  asset('storage/' . $employee->picture->path .  $employee->picture->file_name);
        return $picture;
    }

    public function create()
    {

        $employees = HrEmployee::where('hr_status_id', 1)->get();

        return view('hr/card/create', compact('employees'));

        $filePath = public_path("employee_card.pdf");
    }

    public function index(Request $request)
    {
        $filePath = public_path("employee_card.pdf");
        $outputFilePath = public_path("sample_output.pdf");
        $this->fillPDFFile($filePath, $outputFilePath);

        return response()->file($outputFilePath);
    }

    public function fillPDFFile($file, $outputFilePath)
    {

        $fpdi = new FPDI;
        $employee = HrEmployee::find(3);
        $picture =  asset('storage/' . $employee->picture->path .  $employee->picture->file_name);
        $count = $fpdi->setSourceFile($file);
        $nameLength = Str::length($employee->full_name);
        $designationLength = Str::length($employee->designation);
        //dd(url('cardVerificationResult') . '/' . $employee->employee_no);
        //"data:image/png;base64,'. DNS2D::getBarcodePNG(url('cardVerificationResult').'/'.$data->employee_no,'QRCODE',5,5). '"
        //dd(floor($this->nameAlignment($nameLength)));
        //echo "<img  src=\"$picture\" alt='barcode'   />";
        //echo '<img src="data:image/png;base64,\DNS2D::getBarcodePNG(\'16\', \'QRCODE\')" alt="barcode" />';
        $url = url('cardVerificationResult') . '/' . $employee->employee_no;
        // echo \DNS2D::getBarcodeHTML("$url", 'QRCODE', 5, 5);
        // // echo '<img src="data:image/png;base64,"' . \DNS2D::getBarcodePNG($url, 'QRCODE', 5, 5) . '"/>';
        $qrCode = \DNS1D::getBarcodePNG('1239', 'QRCODE', 5, 5);
        echo  "<img src=$qrCode alt='barcode'   />";
        dd();
        for ($i = 1; $i <= $count; $i++) {

            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);

            $fpdi->SetFont("arial", "", 8);
            $fpdi->SetTextColor(0, 0, 0);

            $employeeNoLeft = 133;
            $employeeNoTop = 15;
            $employeeNo = $employee->employee_no;
            $fpdi->Text($employeeNoLeft, $employeeNoTop, $employeeNo);

            $cnicLeft = 133;
            $cnicTop = 20;
            $employeeCnic = $employee->cnic;
            $fpdi->Text($cnicLeft, $cnicTop, $employeeCnic);

            $contactNoLeft = 133;
            $contactNoTop = 25;
            $employeeContact = $employee->hrEmergency->mobile ?? '';
            $fpdi->Text($contactNoLeft, $contactNoTop, $employeeContact);



            $fpdi->SetFont("arial", "B", 8);

            $nameLeft = 54 + $this->nameAlignment($nameLength);
            $NameTop = 64;
            $employeeName = strtoupper($employee->full_name);
            $fpdi->Text($nameLeft, $NameTop, $employeeName);

            $designationLeft = 54 + $this->nameAlignment($nameLength);
            $designationTop = 69;
            $employeeDesignation = $employee->designation;
            $fpdi->Text($designationLeft, $designationTop, $employeeDesignation);
            $fpdi->Image($picture, 70, 32, 20);
            // $fpdi->Image("https://www.itsolutionstuff.com/assets/images/footer-logo.png", 40, 90);
            $fpdi->Text(54, 75, \DNS2D::getBarcodeHTML("$url", 'QRCODE', 5, 5));
            // $fpdi->Image("data:image/png;base64,'. \DNS2D::getBarcodePNG(url('cardVerificationResult').'/'.$employee->employee_no,'QRCODE',5,5). '", 70, 70, 20, 20, 'png');
        }

        return $fpdi->Output($outputFilePath, 'F');
    }


    public function nameAlignment($nameLength)
    {

        if ($nameLength > 25) {
            return 0;
        }
        $balance = (54 - ($nameLength * 2)) / 2;
        return $balance;
    }
}
