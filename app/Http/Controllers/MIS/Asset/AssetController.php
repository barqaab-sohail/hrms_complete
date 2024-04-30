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

            $data[] =  array(
                'Name' => gettype($asset)
            );
        }
        return response()->json($data);
    }
}
