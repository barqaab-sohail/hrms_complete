<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Admin\AuditResultStore;
use App\Models\Admin\Audit;
use App\Models\Hr\EmployeeAppointment;
use App\User;


class AuditController extends Controller
{
    public function search(){
    	$users = User::all();

        $appNamespace = \Illuminate\Container\Container::getInstance()->getNamespace();
        $modelNamespace = 'Models';

        $models = collect(File::allFiles(app_path($modelNamespace)))->map(function ($item) use ($appNamespace, $modelNamespace) {
            $rel   = $item->getRelativePathName();
            $class = sprintf('\%s%s%s', $appNamespace, $modelNamespace ? $modelNamespace . '\\' : '',
                implode('\\', explode('/', substr($rel, 0, strrpos($rel, '.')))));
            return class_exists($class) ? $class : null;
        })->filter();

        return view ('admin.audit.search',compact('users','models'));
    }


    public function result(AuditResultStore $request){

    	// $EmployeeAppointment = EmployeeAppointment::first();
    	// $result = $EmployeeAppointment->audits()->with('user')->get();
    	// return view('admin.audit.searchResult',compact('result'));

        if($request->filled('model')){
            $model = $request->model;
            $model = ltrim($model, "\\");  
            $result = Audit::where('auditable_type',$model)->latest()->take($request->total_records)->get();
            return view('admin.audit.searchResult',compact('result'));
        }


       
	  	if($request->filled('user')){
	  		$result = Audit::where('user_id',$request->user)->latest()->take($request->total_records)->get();
	  		return view('admin.audit.searchResult',compact('result'));
	  	}

    }
}
