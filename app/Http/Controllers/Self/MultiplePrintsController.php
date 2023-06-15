<?php

namespace App\Http\Controllers\Self;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MultiplePrintsController extends Controller
{
    public function print()
    {

        return view('self.multiplePrint.create');
    }
    public function output(Request $request)
    {
        $image1 = $request->image1;  // your base64 encoded
        $image1 = str_replace('data:image/png;base64,', '', $image1);
        $image1 = str_replace(' ', '+', $image1);
        $imageName1 = 'first_image' . '.' . 'png';
        \File::put(public_path() . '/' . $imageName1, base64_decode($image1));
        if ($request->image2) {
            $image2 = $request->image2;  // your base64 encoded
            $image2 = str_replace('data:image/png;base64,', '', $image2);
            $image2 = str_replace(' ', '+', $image2);
            $imageName2 = 'second_image' . '.' . 'png';
            \File::put(public_path() . '/' . $imageName2, base64_decode($image2));
        }
        return response()->json('File add sucessfully');
    }
}
