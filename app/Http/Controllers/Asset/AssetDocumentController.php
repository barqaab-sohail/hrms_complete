<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AssetDocumentController extends Controller
{
    

    public function create(Request $request){


    	if($request->ajax()){
            return view ('asset.document.create');
        }else{
            return back()->withError('Please contact to administrator, SSE_JS');
        }

    }
}
