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
                //'picture'=> asset('/storage/' . $asset->asPicture->path . $asset->asPicture->file_name),
                'picture' => "https://barqaab.pk/hrms/storage/" . $asset->asPicture->path . $asset->asPicture->file_name,
                'location' => $location,
                'allocation' => $allocation,
            );
        }
        return response()->json($data);
    }
}
