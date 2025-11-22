<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Asset\AsDisposalStore;
use App\Models\Asset\AssetDisposal;
use App\Models\Asset\Asset;
use DB;

class AsDisposalController extends Controller
{
    public function edit(Request $request, $id)
    {
        $data = Asset::with('disposal')->find($id);

        if ($request->ajax()) {
            $view = view('asset.disposal.edit', compact('data'))->render();
            return response()->json($view);
        } else {
            return back()->withError('Please contact to administrator, SSE_JS');
        }
    }

    public function update(AsDisposalStore $request, $id)
    {
        $input = $request->all();

        // Format dates
        if ($request->filled('sold_date')) {
            $input['sold_date'] = \Carbon\Carbon::parse($request->sold_date)->format('Y-m-d');
        }

        // Format sold price - remove commas for database storage
        if ($request->filled('sold_price')) {
            $input['sold_price'] = (int)str_replace(',', '', $input['sold_price']);
        }

        DB::transaction(function () use ($input, $id) {
            AssetDisposal::updateOrCreate(
                ['asset_id' => $id],
                [
                    'sold_date' => $input['sold_date'],
                    'sold_price' => $input['sold_price'],
                    'reason' => $input['reason'],
                    'sold_to' => $input['sold_to'],
                    'notes' => $input['notes']
                ]
            );

        }); // end transaction

        return response()->json(['status' => 'OK', 'message' => "Disposal Data Successfully Updated"]);
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $disposal = AssetDisposal::where('asset_id', $id)->first();
            if ($disposal) {
                $disposal->delete();
            }
        });

        return response()->json(['status' => 'OK', 'message' => "Disposal Data Successfully Deleted"]);
    }
}