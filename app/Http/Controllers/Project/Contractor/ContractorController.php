<?php

namespace App\Http\Controllers\Project\Contractor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Project\Contractor\PrContractor;
use App\Http\Requests\Project\Contractor\ContractorStore;
use DB;
use DataTables;

class ContractorController extends Controller
{
    public function index()
    {

        $prContractor = PrContractor::where('pr_detail_id', session('pr_detail_id'))->get();
        $view =  view('project.contractor.create', compact('prContractor'))->render();
        return response()->json($view);
    }

    public function create(Request $request)
    {

        if ($request->ajax()) {
            $data = PrContractor::where('pr_detail_id', session('pr_detail_id'))->latest()->get();

            return DataTables::of($data)

                ->editColumn('scope_of_work', function ($row) {
                    return Str::of($row->scope_of_work)->limit(50);
                })
                ->addColumn('edit', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editContractor">Edit</a>';

                    return $btn;
                })
                ->addColumn('delete', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteContractor">Delete</a>';

                    return $btn;
                })

                ->rawColumns(['edit', 'delete'])
                ->make(true);
        }
    }

    public function store(ContractorStore $request)
    {

        $input = $request->all();
        $input['pr_detail_id'] = session('pr_detail_id');


        DB::transaction(function () use ($input, $request) {

            PrContractor::updateOrCreate(['id' => $input['contractor_id']], $input);
        }); // end transcation

        return response()->json(['success' => 'Data saved successfully.']);
    }

    public function edit($Id)
    {
        $data = PrContractor::find($Id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        PrContractor::findOrFail($id)->delete();
        return response()->json(['status' => 'OK', 'message' => "Data Successfully Deleted"]);
    }
}
