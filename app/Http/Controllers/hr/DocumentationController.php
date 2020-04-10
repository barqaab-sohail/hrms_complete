<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hr\HrDocumentName;
use App\Http\Requests\Hr\DocumentationStore;

class DocumentationController extends Controller
{
    public function create(){

    	$documentNames = HrDocumentName::all();

    	return view ('hr.documentation.create',compact('documentNames'));
    }

    public function store(DocumentationStore $request){


    	return response()->json(['status'=> 'OK', 'message' => 'Data Sucessfully Saved']);

    }
}
