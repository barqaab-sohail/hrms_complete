<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project\PrDetail;

class ProjectInvoiceController extends Controller
{
    
    public function chart(){

    	$project = PrDetail::where('id','=',session('pr_detail_id'))->first();
    	//2039
    	if($project->id == 4){
    		return view ('project.charts.invoice.invoiceChart');
    	}


    }
}
