<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project\PrDetail;
use Illuminate\Http\Request;

class ProjectLedgerUrlController extends Controller
{
    public function index()
    {


        $projects = PrDetail::whereNotIn('name', array('overhead'))->get();
        $view =  view('project.ledgerUrl.create', compact('projects',))->render();
        return $view;
    }
}
