<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Hr\HrDocumentation;
use App\Models\Hr\HrEmployee;

class PictureController extends Controller
{
    public function edit($id) 
    {
        //return view('demos.jqueryimageupload');
       	$data = HrEmployee::find(session('hr_employee_id'));
        $picture = HrDocumentation::where([['hr_employee_id', '=',session('hr_employee_id')], ['description','=','picture'] ])->first();

        return view ('hr.login.picture', compact('picture','data'));
    }

    /**
     * To handle the comming post request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     function store(Request $request)
    {
     if($request->ajax())
     {
      $image_data = $request->image;
      $image_array_1 = explode(";", $image_data);
      $image_array_2 = explode(",", $image_array_1[1]);
      $data = base64_decode($image_array_2[1]);
      $image_name = time() . '.png';
      $upload_path = public_path('storage/pictures/' . $image_name);
      file_put_contents($upload_path, $data);
      return response()->json(['path' => 'pictures/' . $image_name]);
     }
    }
}
