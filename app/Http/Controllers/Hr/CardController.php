<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use App\Models\Hr\HrEmployee;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use DNS1D;

class CardController extends Controller
{

    public function create()
    {

        $employees = HrEmployee::where('hr_status_id', 1)->get();
        return view('hr/card/create', compact('employees'));
    }

    public function getEmployeePicture($employeeId)
    {
        $employee = HrEmployee::find($employeeId);
        // $picture =  asset('storage/' . $employee->picture->path .  $employee->picture->file_name);
        return $employee->picture;
    }


    public function index(Request $request)
    {

        $employeeid = $request->employeeId;
        $image = $request->image;
        $filePath = public_path("employee_card.pdf");
        $outputFilePath = public_path("sample_output.pdf");
        $this->fillPDFFile($filePath, $outputFilePath, $employeeid, $image);

        return response()->json("Card Created Sucessfully");
    }

    public function fillPDFFile($file, $outputFilePath,  $employeeid, $image)
    {

        $fpdi = new FPDI;
        $employee = HrEmployee::find($employeeid);
        $picture =  $employee->picture;
        $count = $fpdi->setSourceFile($file);
        $nameLength = Str::length($employee->full_name);
        $designationLength = Str::length($employee->designation);

        for ($i = 1; $i <= $count; $i++) {

            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);

            $fpdi->SetFont("arial", "B", 7);
            $fpdi->SetTextColor(0, 0, 0);

            $employeeNoLeft = 135;
            $employeeNoTop = 15;
            $employeeNo = $employee->employee_no;
            $fpdi->Text($employeeNoLeft, $employeeNoTop, $employeeNo);

            $cnicLeft = 135;
            $cnicTop = 20;
            $employeeCnic = $employee->cnic;
            $fpdi->Text($cnicLeft, $cnicTop, $employeeCnic);

            $contactNoLeft = 135;
            $contactNoTop = 27;
            $employeeContact = $employee->hrEmergency->mobile ?? '';
            $fpdi->Text($contactNoLeft, $contactNoTop, $employeeContact);



            $fpdi->SetFont("arial", "B", 8);


            $employeeName = strtoupper($employee->full_name);
            $employeeDesignation = $employee->designation;

            $fpdi->Image($image, 70, 32, 20, 0, 'png');
            $fpdi->SetXY(54, 62);
            $fpdi->MultiCell(50, 3, $txt = "$employeeName \n$employeeDesignation", 0, 'C', 0, 8);
            $filePath = public_path('barcode.png');
            $url = url('cardVerificationResult') . '/' . $employee->employee_no;
            $data =  'data:image/png;base64,' . \DNS2D::getBarcodePNG($url, 'QRCODE', 5, 5);
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            file_put_contents($filePath, $data);
            $fpdi->Image($filePath, 72, 72, 15);
            unlink($filePath);

            // $fpdi->Image($filePath, 72, 72, 15);
            //unlink($filePath);
            //$fpdi->Image($qrCode, 72, 72, 15, 'png');
            // $fpdi->Image("https://www.itsolutionstuff.com/assets/images/footer-logo.png", 40, 90);
            // $fpdi->Image("data:image/png;base64,'. \DNS2D::getBarcodePNG(url('cardVerificationResult').'/'.$employee->employee_no,'QRCODE',5,5). '", 70, 70, 20, 20, 'png');
        }

        $fpdi->Output($outputFilePath, 'F');
    }
}
