<?php

namespace App\Http\Controllers\MIS\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset\Asset;
use App\Models\Asset\AsSubClass;

class AssetController extends Controller
{
    public function index()
    {
        $data = Asset::with('companyAsset', 'asPicture', 'asCurrentAllocation', 'asCurrentLocation')->get();

        $defaultPicture = asset('Massets/images/default.png');
        foreach ($data as $asset) {
            if ($asset->asPicture) {
                $picture = asset('storage/' . $asset->asPicture->path . $asset->asPicture->file_name);
            } else {
                $picture = $defaultPicture;
            }
            $assets[] =  array(
                "id" => $asset->id,
                "asset_code" => $asset->asset_code,
                "name" => $asset->description,
                "allocation" => $asset->asCurrentAllocation->full_name ?? '',
                "location" => $asset->asCurrentLocation->name ?? '',
                "picture" => $picture,

            );
        }
        return response()->json($assets);
    }

    public function assetSubClasses()
    {

        $subClasses = AsSubClass::all();
        $asset = Asset::with('asClass', 'currentOwnership')->get();
        foreach ($subClasses as $subClass) {
            if ($asset->where('as_sub_class_id', $subClass->id)->count() != 0) {
                $data[] =  array(
                    'id' => $subClass->id,
                    'name' => $subClass->name,
                    'count' => $asset->where('as_sub_class_id', $subClass->id)->count(),
                );
            }
        }
        // $data = $asset->where('as_sub_class_id',1)->count();
        return response()->json($data);
    }

    public function subClassList($subClassId)
    {
        $assets = Asset::where('as_sub_class_id', $subClassId)->with('asOwnership', 'asCurrentAllocation', 'asCurrentLocation', 'asPicture')->get();

        foreach ($assets as $asset) {
            $location = $asset->asCurrentLocation->name ?? '';
            $employee = $asset->asCurrentAllocation->full_name ?? '';
            $designation = $asset->asCurrentAllocation->designation ?? '';
            $allocation = $employee != "" ? $employee . ', ' . $designation : '';
            $data[] = array(
                'name' => $asset->description ?? '',
                'id'=>$asset->id,
                //'picture'=> asset('/storage/' . $asset->asPicture->path . $asset->asPicture->file_name),
                'picture' => "https://barqaab.pk/hrms/storage/" . $asset->asPicture->path . $asset->asPicture->file_name,
                'location' => $location,
                'allocation' => $allocation,
            );
        }
        return response()->json($data);
    }

    public function asset($assetId){

        $data =  Asset::with('asDocumentations','asCurrentAllocation','asConsumables','asMaintenances','asCondition','asPurchaseCondition')->find($assetId);

        $allocation = $data->asCurrentAllocation;
        $maintenances = $data->asMaintenances;
        if($maintenances->sum('maintenance_cost')=='0'){
            $maintenances =null;
            $maintenanceTo = null;
            $maintenanceFrom = null;
        }else{
            $maintenanceTo = \Carbon\Carbon::parse($maintenances->max('maintenance_date'))->format('M d, Y');
            $maintenanceFrom = \Carbon\Carbon::parse($maintenances->min('maintenance_date'))->format('M d, Y');
        }

        $consumables = $data->asConsumables;
        if($consumables->sum('consumable_cost')=='0'){
            $consumables =null;
            $consumablesTo = null;
            $consumablesFrom = null;
        }else{
            $consumablesTo = \Carbon\Carbon::parse($consumables->max('consumable_date'))->format('M d, Y');
            $consumablesFrom = \Carbon\Carbon::parse($consumables->min('consumable_date'))->format('M d, Y');
        }

        $condition = $data->asCondition?$data->asCondition->name:'Working';
        

        $asset = [
            'id'=>$data->id,
            'description'=>$data->description,
            'asset_code'=>$data->asset_code,
            'asset_picture'=>$data->picture??'',
            'condition'=>$condition,
            'purchase_condition'=>$data->asPurchaseCondition?->name,
            'purchase_date'=>$data->asPurchase?\Carbon\Carbon::parse($data->asPurchase->purchase_date)->format('M d, Y'):'',
            'purchase_cost'=>$data->asPurchase?number_format($data->asPurchase->purchase_cost,0):'',
            'location_office'=>$data->asCurrentLocation?->name,
            'location_address'=>$data->asCurrentLocation?->address,
            'allocation_name'=>$allocation?->full_name,
            'allocation_designation'=>$allocation?->designation,
            'allocation_picture'=>$allocation?->picture,
            'maintenance_cost'=>$maintenances?number_format($maintenances->sum('maintenance_cost'),0):'',
            'maintenance_from'=>$maintenanceFrom,
            'maintenance_to'=>$maintenanceTo,
            'maintenances'=>$maintenances,
            'consumable_cost'=>$consumables?number_format($consumables->sum('consumable_cost'),0):'',
            'consumable_from'=>$consumablesFrom,
            'consumable_to'=>$consumablesTo,
            'consumables'=>$consumables,
            'documents'=>$data->asDocumentations,
            
        ];

        return $asset;

    }
}
