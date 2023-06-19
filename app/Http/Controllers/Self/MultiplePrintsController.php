<?php

namespace App\Http\Controllers\Self;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;

class MultiplePrintsController extends Controller
{
    public function print()
    {

        return view('self.multiplePrint.create');
    }
    public function output(Request $request)
    {
        $image1 = $request->image1;
        $image2 = $request->image2;
        $filePath = public_path("multiple_prints.pdf");
        $outputFilePath = public_path("prints_output.pdf");
        $this->fillPDFFile($filePath, $outputFilePath, $image1, $image2);

        // $image1 = $request->image1;  // your base64 encoded
        // $image1 = str_replace('data:image/png;base64,', '', $image1);
        // $image1 = str_replace(' ', '+', $image1);
        // $imageName1 = 'first_image' . '.' . 'png';
        // \File::put(public_path() . '/' . $imageName1, base64_decode($image1));
        // if ($request->image2) {
        //     $image2 = $request->image2;  // your base64 encoded
        //     $image2 = str_replace('data:image/png;base64,', '', $image2);
        //     $image2 = str_replace(' ', '+', $image2);
        //     $imageName2 = 'second_image' . '.' . 'png';
        //     \File::put(public_path() . '/' . $imageName2, base64_decode($image2));
        // }
        return response()->json('File add sucessfully');
    }

    public function fillPDFFile($file, $outputFilePath,  $image1, $image2 = null)
    {

        $fpdi = new FPDI;
        $count = $fpdi->setSourceFile($file);

        //Page -1
        $template = $fpdi->importPage(1);
        $size = $fpdi->getTemplateSize($template);
        $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
        $fpdi->useTemplate($template);
        $fpdi->Image($image1, 13, 20, 90, 0, 'png');
        $fpdi->Image($image1, 107, 20, 90, 0, 'png');
        $fpdi->Image($image1, 13, 85, 90, 0, 'png');
        $fpdi->Image($image1, 107, 85, 90, 0, 'png');
        $fpdi->Image($image1, 13, 150, 90, 0, 'png');
        $fpdi->Image($image1, 107, 150, 90, 0, 'png');
        $fpdi->Image($image1, 13, 215, 90, 0, 'png');
        $fpdi->Image($image1, 107, 215, 90, 0, 'png');

        //Page 2
        if ($image2) {
            $template = $fpdi->importPage(2);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);
            $fpdi->Image($image2, 11, 20, 90, 0, 'png');
            $fpdi->Image($image2, 105, 20, 90, 0, 'png');
            $fpdi->Image($image2, 11, 85, 90, 0, 'png');
            $fpdi->Image($image2, 105, 85, 90, 0, 'png');
            $fpdi->Image($image2, 11, 150, 90, 0, 'png');
            $fpdi->Image($image2, 105, 150, 90, 0, 'png');
            $fpdi->Image($image2, 11, 215, 90, 0, 'png');
            $fpdi->Image($image2, 105, 215, 90, 0, 'png');
        }
        $fpdi->Output($outputFilePath, 'F');
    }
}
